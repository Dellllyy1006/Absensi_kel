<?php
/**
 * Application Configuration
 * 
 * Konfigurasi umum aplikasi
 */

// Auto-detect Base URL
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
define('BASE_URL', $protocol . '://' . $host . '/absensi_kel/public');

// Application Settings
define('APP_NAME', 'Absensi SMK');
define('APP_VERSION', '1.0.0');

// Timezone
date_default_timezone_set('Asia/Jakarta');

// Session Configuration
define('SESSION_LIFETIME', 3600); // 1 hour

// Upload Directories
define('UPLOAD_PATH', __DIR__ . '/../public/uploads/');
define('PHOTO_PATH', UPLOAD_PATH . 'photos/');
define('QRCODE_PATH', UPLOAD_PATH . 'qrcodes/');

// QR Code Settings
define('QR_SESSION_DURATION', 30); // minutes
