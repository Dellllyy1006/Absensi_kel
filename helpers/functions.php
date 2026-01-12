<?php
/**
 * Helper Functions
 * 
 * Utility functions for the application
 */

/**
 * Escape HTML output
 * 
 * @param string|null $string
 * @return string
 */
function e(?string $string): string
{
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Generate URL
 * 
 * @param string $path
 * @return string
 */
function url(string $path = ''): string
{
    return BASE_URL . $path;
}

/**
 * Asset URL
 * 
 * @param string $path
 * @return string
 */
function asset(string $path): string
{
    return BASE_URL . '/' . ltrim($path, '/');
}

/**
 * Format date to Indonesian
 * 
 * @param string $date
 * @param string $format
 * @return string
 */
function formatDate(string $date, string $format = 'd F Y'): string
{
    $months = [
        'January' => 'Januari',
        'February' => 'Februari',
        'March' => 'Maret',
        'April' => 'April',
        'May' => 'Mei',
        'June' => 'Juni',
        'July' => 'Juli',
        'August' => 'Agustus',
        'September' => 'September',
        'October' => 'Oktober',
        'November' => 'November',
        'December' => 'Desember'
    ];

    $formatted = date($format, strtotime($date));
    return str_replace(array_keys($months), array_values($months), $formatted);
}

/**
 * Format time
 * 
 * @param string $time
 * @return string
 */
function formatTime(string $time): string
{
    return date('H:i', strtotime($time));
}

/**
 * Get status badge class
 * 
 * @param string $status
 * @return string
 */
function getStatusBadge(string $status): string
{
    $badges = [
        'hadir' => 'badge-success',
        'izin' => 'badge-info',
        'sakit' => 'badge-warning',
        'alpha' => 'badge-danger'
    ];

    return $badges[$status] ?? 'badge-secondary';
}

/**
 * Get status label
 * 
 * @param string $status
 * @return string
 */
function getStatusLabel(string $status): string
{
    $labels = [
        'hadir' => 'Hadir',
        'izin' => 'Izin',
        'sakit' => 'Sakit',
        'alpha' => 'Alpha'
    ];

    return $labels[$status] ?? $status;
}

/**
 * Get gender label
 * 
 * @param string $gender
 * @return string
 */
function getGenderLabel(string $gender): string
{
    return $gender === 'L' ? 'Laki-laki' : 'Perempuan';
}

/**
 * Check if current page
 * 
 * @param string $path
 * @return bool
 */
function isCurrentPage(string $path): bool
{
    $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $basePath = '/absensi_kel/public';
    $relativePath = str_replace($basePath, '', $currentPath);
    
    return strpos($relativePath, $path) === 0;
}

/**
 * Get month name in Indonesian
 * 
 * @param int $month
 * @return string
 */
function getMonthName(int $month): string
{
    $months = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
        4 => 'April', 5 => 'Mei', 6 => 'Juni',
        7 => 'Juli', 8 => 'Agustus', 9 => 'September',
        10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];

    return $months[$month] ?? '';
}

/**
 * Generate CSRF token
 * 
 * @return string
 */
function csrfToken(): string
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * CSRF input field
 * 
 * @return string
 */
function csrfField(): string
{
    return '<input type="hidden" name="csrf_token" value="' . csrfToken() . '">';
}

/**
 * Verify CSRF token
 * 
 * @param string $token
 * @return bool
 */
function verifyCsrf(string $token): bool
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Truncate text
 * 
 * @param string $text
 * @param int $length
 * @return string
 */
function truncate(string $text, int $length = 100): string
{
    if (strlen($text) <= $length) {
        return $text;
    }
    return substr($text, 0, $length) . '...';
}

/**
 * Debug dump
 * 
 * @param mixed $data
 * @return void
 */
function dd($data): void
{
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
    die();
}
