<?php
/**
 * Application Entry Point
 * 
 * Simple routing dan autoloading
 */

// Error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load configuration
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/app.php';

// Load helpers
require_once __DIR__ . '/../helpers/functions.php';
require_once __DIR__ . '/../helpers/validation.php';

// Simple autoloader
spl_autoload_register(function ($class) {
    // Convert namespace to file path
    $classMap = [
        'Core\\' => __DIR__ . '/../core/',
        'App\\' => __DIR__ . '/../app/',
    ];

    foreach ($classMap as $prefix => $baseDir) {
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) === 0) {
            $relativeClass = substr($class, $len);
            $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
            
            if (file_exists($file)) {
                require_once $file;
                return;
            }
        }
    }
});

// Start session
session_start();

// Get the request URI
$requestUri = $_SERVER['REQUEST_URI'];
$basePath = '/absensi_kel/public';
$path = str_replace($basePath, '', parse_url($requestUri, PHP_URL_PATH));
$path = $path ?: '/';

// Simple routing
$routes = [
    // Auth routes
    'GET:/' => ['App\\Controllers\\AuthController', 'loginForm'],
    'GET:/auth/login' => ['App\\Controllers\\AuthController', 'loginForm'],
    'POST:/auth/login' => ['App\\Controllers\\AuthController', 'login'],
    'GET:/auth/register' => ['App\\Controllers\\AuthController', 'registerForm'],
    'POST:/auth/register' => ['App\\Controllers\\AuthController', 'register'],
    'GET:/auth/logout' => ['App\\Controllers\\AuthController', 'logout'],
    
    // Dashboard routes
    'GET:/dashboard' => ['App\\Controllers\\DashboardController', 'index'],
    
    // Profile routes
    'GET:/profile' => ['App\\Controllers\\ProfileController', 'index'],
    'GET:/profile/edit' => ['App\\Controllers\\ProfileController', 'edit'],
    'POST:/profile/update' => ['App\\Controllers\\ProfileController', 'update'],
    'POST:/profile/generate-qr' => ['App\\Controllers\\ProfileController', 'generateQR'],
    'GET:/profile/siswa' => ['App\\Controllers\\ProfileController', 'viewSiswa'],
    
    // Absensi routes
    'GET:/absensi' => ['App\\Controllers\\AbsensiController', 'index'],
    'GET:/absensi/scan' => ['App\\Controllers\\AbsensiController', 'scan'],
    'POST:/absensi/process-scan' => ['App\\Controllers\\AbsensiController', 'processScan'],
    'GET:/absensi/history' => ['App\\Controllers\\AbsensiController', 'history'],
    'GET:/absensi/manual' => ['App\\Controllers\\AbsensiController', 'manual'],
    'POST:/absensi/manual' => ['App\\Controllers\\AbsensiController', 'processManual'],
    'GET:/absensi/bulk' => ['App\\Controllers\\AbsensiController', 'bulk'],
    'POST:/absensi/bulk' => ['App\\Controllers\\AbsensiController', 'processBulk'],
    'POST:/absensi/checkout' => ['App\\Controllers\\AbsensiController', 'checkOut'],
    'GET:/absensi/edit' => ['App\\Controllers\\AbsensiController', 'edit'],
    'POST:/absensi/update' => ['App\\Controllers\\AbsensiController', 'update'],
    'GET:/absensi/delete' => ['App\\Controllers\\AbsensiController', 'delete'],
    
    // QR routes
    'GET:/qr' => ['App\\Controllers\\QRController', 'index'],
    'POST:/qr/create' => ['App\\Controllers\\QRController', 'create'],
    'GET:/qr/deactivate' => ['App\\Controllers\\QRController', 'deactivate'],
    'GET:/qr/display' => ['App\\Controllers\\QRController', 'display'],
    'GET:/qr/status' => ['App\\Controllers\\QRController', 'status'],
];

// Get method and create route key
$method = $_SERVER['REQUEST_METHOD'];
$routeKey = $method . ':' . $path;

// Handle route
if (isset($routes[$routeKey])) {
    [$controllerClass, $action] = $routes[$routeKey];
    
    $controller = new $controllerClass();
    $controller->$action();
} else {
    // 404 Not Found
    http_response_code(404);
    echo '<h1>404 - Page Not Found</h1>';
    echo '<p>The page you are looking for does not exist.</p>';
    echo '<a href="' . url('/') . '">Go to Home</a>';
}
