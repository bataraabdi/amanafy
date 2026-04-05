<?php
/**
 * Helper Functions
 */

/**
 * Sanitize output for XSS protection
 */
function e(?string $value): string {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Format number as Indonesian Rupiah
 */
function rupiah(float $amount): string {
    return 'Rp ' . number_format($amount, 0, ',', '.');
}

/**
 * Format number with dots (no Rp prefix)
 */
function formatNumber(float $amount): string {
    return number_format($amount, 0, ',', '.');
}

/**
 * Normalize currency string input into float.
 */
function normalizeAmountInput(?string $value): float {
    $normalized = trim((string)$value);
    if ($normalized === '') {
        return 0;
    }

    // Remove any non-numeric characters except comma and dot
    $normalized = preg_replace('/[^0-9\.,]/', '', $normalized);

    // Standardize: Indonesian typical input 1.000,00 -> 1000.00
    // But sometimes people just type 1000 or 1.000
    $normalized = str_replace('.', '', $normalized);
    $normalized = str_replace(',', '.', $normalized);

    return (float)$normalized;
}

/**
 * Format date to Indonesian format
 */
function formatDate(?string $date): string {
    if (empty($date)) return '-';
    $months = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    $timestamp = strtotime($date);
    $day = date('d', $timestamp);
    $month = $months[(int)date('m', $timestamp)];
    $year = date('Y', $timestamp);
    return "{$day} {$month} {$year}";
}

/**
 * Format datetime
 */
function formatDateTime(?string $datetime): string {
    if (empty($datetime)) return '-';
    $timestamp = strtotime($datetime);
    return formatDate($datetime) . ' ' . date('H:i', $timestamp);
}

/**
 * Get flash message and clear it
 */
function getFlash(string $type): ?string {
    $key = 'flash_' . $type;
    if (isset($_SESSION[$key])) {
        $msg = $_SESSION[$key];
        unset($_SESSION[$key]);
        return $msg;
    }
    return null;
}

/**
 * Check if current URL matches
 */
function isActiveMenu(string $path): string {
    $currentUrl = $_GET['url'] ?? '';
    $currentUrl = rtrim($currentUrl, '/');
    $path = rtrim($path, '/');
    
    if ($path === '' && $currentUrl === '') return 'active';
    if ($path !== '' && strpos($currentUrl, $path) === 0) return 'active';
    return '';
}

/**
 * Generate pagination HTML
 */
function pagination(int $totalItems, int $perPage, int $currentPage, string $baseUrl): string {
    $totalPages = max(1, ceil($totalItems / $perPage));
    if ($totalPages <= 1) return '';

    $html = '<nav><ul class="pagination justify-content-center">';
    
    // Previous
    $prevDisabled = $currentPage <= 1 ? 'disabled' : '';
    $prevPage = max(1, $currentPage - 1);
    $html .= "<li class='page-item {$prevDisabled}'><a class='page-link' href='{$baseUrl}?page={$prevPage}'>&laquo;</a></li>";

    // Page numbers
    $start = max(1, $currentPage - 2);
    $end = min($totalPages, $currentPage + 2);

    if ($start > 1) {
        $html .= "<li class='page-item'><a class='page-link' href='{$baseUrl}?page=1'>1</a></li>";
        if ($start > 2) $html .= "<li class='page-item disabled'><span class='page-link'>...</span></li>";
    }

    for ($i = $start; $i <= $end; $i++) {
        $active = $i === $currentPage ? 'active' : '';
        $html .= "<li class='page-item {$active}'><a class='page-link' href='{$baseUrl}?page={$i}'>{$i}</a></li>";
    }

    if ($end < $totalPages) {
        if ($end < $totalPages - 1) $html .= "<li class='page-item disabled'><span class='page-link'>...</span></li>";
        $html .= "<li class='page-item'><a class='page-link' href='{$baseUrl}?page={$totalPages}'>{$totalPages}</a></li>";
    }

    // Next
    $nextDisabled = $currentPage >= $totalPages ? 'disabled' : '';
    $nextPage = min($totalPages, $currentPage + 1);
    $html .= "<li class='page-item {$nextDisabled}'><a class='page-link' href='{$baseUrl}?page={$nextPage}'>&raquo;</a></li>";

    $html .= '</ul></nav>';
    return $html;
}

/**
 * Get current month/year
 */
function currentMonth(): string {
    return date('Y-m');
}

function currentYear(): string {
    return date('Y');
}

/**
 * Truncate string
 */
function truncate(string $text, int $length = 100): string {
    if (strlen($text) <= $length) return $text;
    return substr($text, 0, $length) . '...';
}

/**
 * Available fund categories for ISAK 35 presentation.
 */
function fundCategoryOptions(): array {
    return ['Tidak Terikat', 'Terikat'];
}

/**
 * Create a string reference for an account select option.
 */
function accountReferenceValue(?string $type, $id): string {
    $type = trim((string)$type);
    $id = (int)$id;

    if (!in_array($type, ['cash', 'bank'], true) || $id <= 0) {
        return '';
    }

    return $type . ':' . $id;
}

/**
 * Format a human-readable account type label.
 */
function accountTypeLabel(string $type): string {
    return $type === 'bank' ? 'Bank' : 'Kas';
}

/**
 * Convert text into a URL-friendly slug.
 */
function slugify(string $text): string {
    $text = trim($text);
    if ($text === '') {
        return '';
    }

    if (function_exists('iconv')) {
        $converted = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
        if ($converted !== false) {
            $text = $converted;
        }
    }

    $text = strtolower($text);
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    $text = trim($text, '-');

    return $text;
}

/**
 * Sanitize simple rich text HTML generated by the note editor.
 */
function sanitizeRichText(?string $html): string {
    $html = trim((string)$html);
    if ($html === '') {
        return '';
    }

    $html = preg_replace('#<\s*(script|style)[^>]*>.*?<\s*/\s*\\1>#is', '', $html);
    $html = strip_tags($html, '<p><br><strong><b><em><i><u><ul><ol><li><blockquote><h2><h3><h4><a>');
    $html = preg_replace('/\s+on[a-z]+\s*=\s*("|\').*?\1/i', '', $html);
    $html = preg_replace('/\s+(class|style|id|target)\s*=\s*("|\').*?\2/i', '', $html);
    $html = preg_replace('/href\s*=\s*("|\')\s*javascript:[^"\']*\1/i', 'href="#"', $html);

    return trim((string)$html);
}

/**
 * Extract plain text from HTML.
 */
function htmlToPlainText(?string $html): string {
    $plain = strip_tags((string)$html);
    $plain = preg_replace('/\s+/', ' ', $plain);
    return trim((string)$plain);
}

/**
 * Decode a JSON array safely.
 */
function decodeJsonArray($value): array {
    if (is_array($value)) {
        return array_values(array_filter($value, fn($item) => is_string($item) && trim($item) !== ''));
    }

    if (!is_string($value) || trim($value) === '') {
        return [];
    }

    $decoded = json_decode($value, true);
    if (!is_array($decoded)) {
        return [];
    }

    return array_values(array_filter($decoded, fn($item) => is_string($item) && trim($item) !== ''));
}

/**
 * Check whether a filename is an image.
 */
function isImageFile(string $filename): bool {
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    return in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'], true);
}

/**
 * Check whether a filename is a video.
 */
function isVideoFile(string $filename): bool {
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    return in_array($ext, ['mp4', 'mov', 'avi', 'mkv', 'webm'], true);
}

/**
 * Build a public URL for a donation program.
 */
function donasiPublicUrl(array $record): string {
    $slug = trim((string)($record['slug'] ?? ''));
    return BASE_URL . '/publik/donasi/' . rawurlencode($slug);
}

/**
 * Generate unique ID for transactions
 */
function generateTransactionId(string $prefix = 'TRX'): string {
    return $prefix . '-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));
}
