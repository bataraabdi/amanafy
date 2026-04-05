<?php
/**
 * Front Controller - Aplikasi Manajemen Keuangan Kas Masjid
 * All requests are routed through this file
 */

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Optimization & Security for Session
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    ini_set('session.cookie_secure', 1);
}

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load configuration
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/database.php';

// Security Headers
header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Referrer-Policy: strict-origin-when-cross-origin");
header("Permissions-Policy: geolocation=(), camera=(), microphone=()");
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://cdn.tailwindcss.com https://code.jquery.com https://cdn.datatables.net; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://fonts.googleapis.com https://cdn.datatables.net; font-src 'self' https://cdn.jsdelivr.net https://fonts.gstatic.com; img-src 'self' data: " . BASE_URL . "/*; frame-ancestors 'none'; connect-src 'self';");

// Load core classes
require_once __DIR__ . '/core/CSRF.php';
require_once __DIR__ . '/core/Security.php';
require_once __DIR__ . '/core/AuditLog.php';
require_once __DIR__ . '/core/Auth.php';
require_once __DIR__ . '/core/Helpers.php';
require_once __DIR__ . '/core/Model.php';
require_once __DIR__ . '/core/Controller.php';
require_once __DIR__ . '/core/Router.php';

// Create upload directories if they don't exist
$uploadDirs = [UPLOAD_PATH, UPLOAD_BUKTI, UPLOAD_LOGO, UPLOAD_KEGIATAN, UPLOAD_DONASI, UPLOAD_QRIS, BACKUP_PATH];
foreach ($uploadDirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

// Route the request
$router = new Router();
$router->dispatch();
