<?php
/**
 * Authentication handler
 */
class Auth {

    public static function attempt(string $username, string $password): bool {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT u.*, r.nama_role, r.hak_akses FROM users u JOIN roles r ON u.role_id = r.id WHERE u.username = :username AND u.status = 'aktif' LIMIT 1");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Set session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role_id'] = $user['role_id'];
            $_SESSION['role_name'] = $user['nama_role'];
            $_SESSION['hak_akses'] = json_decode($user['hak_akses'], true) ?? [];
            $_SESSION['logged_in'] = true;
            $_SESSION['login_time'] = time();
            $_SESSION['last_activity'] = time();

            // Update last login
            $updateStmt = $db->prepare("UPDATE users SET last_login = NOW() WHERE id = :id");
            $updateStmt->execute([':id' => $user['id']]);

            // Audit log
            AuditLogger::log('LOGIN', 'users', $user['id'], null, ['username' => $user['username']]);

            return true;
        }

        return false;
    }

    public static function check(): bool {
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            return false;
        }

        // Check session timeout (Idle timeout)
        $lastActivity = $_SESSION['last_activity'] ?? $_SESSION['login_time'] ?? time();
        if ((time() - $lastActivity) > SESSION_LIFETIME) {
            self::logout();
            return false;
        }

        // Update last activity
        $_SESSION['last_activity'] = time();

        return true;
    }

    public static function user(): ?array {
        if (!self::check()) return null;
        return [
            'id' => $_SESSION['user_id'] ?? null,
            'username' => $_SESSION['username'] ?? null,
            'nama_lengkap' => $_SESSION['nama_lengkap'] ?? null,
            'email' => $_SESSION['email'] ?? null,
            'role_id' => $_SESSION['role_id'] ?? null,
            'role_name' => $_SESSION['role_name'] ?? null,
            'hak_akses' => $_SESSION['hak_akses'] ?? [],
        ];
    }

    public static function id(): ?int {
        return $_SESSION['user_id'] ?? null;
    }

    public static function hasRole(array $roles): bool {
        $userRole = $_SESSION['role_name'] ?? '';
        // Super Admin has all access
        if ($userRole === 'Super Admin') return true;
        return in_array($userRole, $roles);
    }

    public static function hasPermission(string $permission): bool {
        $permissions = $_SESSION['hak_akses'] ?? [];
        if (in_array('all', $permissions)) return true;
        return in_array($permission, $permissions);
    }

    public static function isSuperAdmin(): bool {
        return ($_SESSION['role_name'] ?? '') === 'Super Admin';
    }

    public static function isBendahara(): bool {
        return ($_SESSION['role_name'] ?? '') === 'Bendahara';
    }

    public static function logout(): void {
        if (isset($_SESSION['user_id'])) {
            AuditLogger::log('LOGOUT', 'users', $_SESSION['user_id'], null, ['username' => $_SESSION['username'] ?? '']);
        }
        session_unset();
        session_destroy();
        session_start();
    }
}
