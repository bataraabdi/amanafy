<?php
/**
 * Security Helper - Brute Force Protection & Honeypot
 */
class Security {

    private const MAX_ATTEMPTS = 5;
    private const LOCKOUT_TIME = 900; // 15 minutes

    private static function ensureTableExists(): void {
        try {
            $db = Database::getConnection();
            $db->exec("CREATE TABLE IF NOT EXISTS `login_attempts` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `ip_address` varchar(45) NOT NULL,
                `username` varchar(50) DEFAULT NULL,
                `attempted_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                KEY `idx_ip_time` (`ip_address`, `attempted_at`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");
        } catch (\PDOException $e) {
            // Silently ignore if insufficient permissions
        }
    }

    public static function checkThrottling(): bool {
        try {
            $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
            $db = Database::getConnection();
            
            $stmt = $db->prepare("SELECT COUNT(*) FROM login_attempts 
                                WHERE ip_address = :ip 
                                AND attempted_at > (NOW() - INTERVAL 15 MINUTE)");
            $stmt->execute([':ip' => $ip]);
            $attempts = $stmt->fetchColumn();

            return (int)$attempts < self::MAX_ATTEMPTS;
        } catch (\PDOException $e) {
            self::ensureTableExists();
            return true;
        }
    }

    public static function recordFailedLogin(string $username): void {
        try {
            $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
            $db = Database::getConnection();
            
            $stmt = $db->prepare("INSERT INTO login_attempts (ip_address, username) VALUES (:ip, :username)");
            $stmt->execute([
                ':ip' => $ip,
                ':username' => $username
            ]);
        } catch (\PDOException $e) {
            self::ensureTableExists();
        }
    }

    public static function clearLoginAttempts(): void {
        try {
            $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
            $db = Database::getConnection();
            
            $stmt = $db->prepare("DELETE FROM login_attempts WHERE ip_address = :ip");
            $stmt->execute([':ip' => $ip]);
            
            // Clean up old attempts for all IPs
            $stmt = $db->prepare("DELETE FROM login_attempts WHERE attempted_at < (NOW() - INTERVAL 24 HOUR)");
            $stmt->execute();
        } catch (\PDOException $e) {
            // Silently ignore
        }
    }

    public static function validateHoneypot(): bool {
        // If the 'email_confirm' field is filled, it's a bot
        return empty($_POST['email_confirm']);
    }

    public static function generateHoneypotField(): string {
        return '<div style="display:none !important; position: absolute; left: -9999px;">
                    <input type="text" name="email_confirm" tabindex="-1" autocomplete="off">
                </div>';
    }
}
