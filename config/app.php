<?php
/**
 * Application Configuration
 */

// Auto-detect base URL
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$baseUrl = $protocol . '://' . $host . ($scriptDir === '/' ? '' : $scriptDir);

define('APP_NAME', 'Kas Masjid');
define('APP_VERSION', '1.4.1`');
define('BASE_URL', rtrim($baseUrl, '/'));
define('BASE_PATH', rtrim(str_replace('\\', '/', dirname(__DIR__)), '/'));

// Directory paths
define('UPLOAD_PATH', BASE_PATH . '/uploads/');
define('UPLOAD_BUKTI', UPLOAD_PATH . 'bukti/');
define('UPLOAD_LOGO', UPLOAD_PATH . 'logo/');
define('UPLOAD_KEGIATAN', UPLOAD_PATH . 'kegiatan/');
define('UPLOAD_DONASI', UPLOAD_PATH . 'donasi/');
define('UPLOAD_QRIS', UPLOAD_PATH . 'qris/');
define('BACKUP_PATH', BASE_PATH . '/backups/');

// Session config
define('SESSION_LIFETIME', 900); // 15 minutes

// Pagination
define('PER_PAGE', 15);

// Timezone
date_default_timezone_set('Asia/Jakarta');
