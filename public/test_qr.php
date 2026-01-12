<?php
// Define minimal environment
define('BASE_URL', 'http://localhost/absensi_kel/public');
define('APP_NAME', 'Absensi SMK');
define('UPLOAD_PATH', __DIR__ . '/uploads/');
define('QRCODE_PATH', UPLOAD_PATH . 'qrcodes/');

// Create directories if not exist
if (!is_dir(QRCODE_PATH)) {
    mkdir(QRCODE_PATH, 0755, true);
    echo "Created directory: " . QRCODE_PATH . "<br>";
}

// Check GD
echo "GD Library: " . (function_exists('imagecreatetruecolor') ? 'Active' : 'Inactive') . "<br>";
echo "Allow URL Fopen: " . (ini_get('allow_url_fopen') ? 'On' : 'Off') . "<br>";

// Test Generation using the logic from Service
$data = json_encode(['test' => '123']);
$filename = 'test_qr.png';
$filepath = QRCODE_PATH . $filename;

echo "Attempting to generate QR at: $filepath<br>";

// Method 1: GD
if (function_exists('imagecreatetruecolor')) {
    echo "Trying GD... ";
    try {
        require_once __DIR__ . '/../vendor/phpqrcode/qrlib.php';
        \QRcode::png($data, $filepath, QR_ECLEVEL_M, 10, 2);
        if (file_exists($filepath)) {
            echo "Success! (GD)<br>";
            echo "<img src='uploads/qrcodes/$filename'>";
            exit;
        } else {
            echo "Failed.<br>";
        }
    } catch (\Exception $e) {
        echo "Error: " . $e->getMessage() . "<br>";
    }
}

// Method 2: Google API
echo "Trying Google Chart API... ";
$url = 'https://chart.googleapis.com/chart?cht=qr&chs=300x300&chl=' . urlencode($data) . '&choe=UTF-8';
$content = @file_get_contents($url);
if ($content) {
    file_put_contents($filepath, $content);
    echo "Success! (Google API)<br>";
    echo "<img src='uploads/qrcodes/$filename'>";
    exit;
} else {
    echo "Failed. Content empty.<br>";
}

// Method 3: QRServer
echo "Trying QRServer API... ";
$url2 = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($data);
$content = @file_get_contents($url2);
if ($content) {
    file_put_contents($filepath, $content);
    echo "Success! (QRServer)<br>";
    echo "<img src='uploads/qrcodes/$filename'>";
    exit;
} else {
    echo "Failed.<br>";
}

echo "All methods failed.";
