<?php
/**
 * Security Helper - Brute Force Protection & Honeypot
 */
class Security {

    private const MAX_ATTEMPTS = 5;
    private const LOCKOUT_TIME = 900; // 15 minutes

    public static function checkThrottling(): bool {
        $ip = $_SERVER['REMOTE_ADDR'];
        $db = Database::getConnection();
        
        $stmt = $db->prepare("SELECT COUNT(*) FROM login_attempts 
                            WHERE ip_address = :ip 
                            AND attempted_at > (NOW() - INTERVAL 15 MINUTE)");
        $stmt->execute([':ip' => $ip]);
        $attempts = $stmt->fetchColumn();

        return (int)$attempts < self::MAX_ATTEMPTS;
    }

    public static function recordFailedLogin(string $username): void {
        $ip = $_SERVER['REMOTE_ADDR'];
        $db = Database::getConnection();
        
        $stmt = $db->prepare("INSERT INTO login_attempts (ip_address, username) VALUES (:ip, :username)");
        $stmt->execute([
            ':ip' => $ip,
            ':username' => $username
        ]);
    }

    public static function clearLoginAttempts(): void {
        $ip = $_SERVER['REMOTE_ADDR'];
        $db = Database::getConnection();
        
        $stmt = $db->prepare("DELETE FROM login_attempts WHERE ip_address = :ip");
        $stmt->execute([':ip' => $ip]);
        
        // Optional: Clean up old attempts for all IPs
        $stmt = $db->prepare("DELETE FROM login_attempts WHERE attempted_at < (NOW() - INTERVAL 24 HOUR)");
        $stmt->execute();
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
