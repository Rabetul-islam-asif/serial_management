<?php

/**
 * Doctor Serial Cloud — Front Controller Entry Point
 */

// 1. Boot Autoloader
if (file_exists(dirname(__DIR__) . '/vendor/autoload.php')) {
    require_once dirname(__DIR__) . '/vendor/autoload.php';
} else {
    // PSR-4 Autoloader Fallback
    spl_autoload_register(function ($class) {
        $prefix = 'App\\';
        $base_dir = dirname(__DIR__) . '/app/';
        
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            return;
        }
        
        $relative_class = substr($class, $len);
        $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
        
        // Convert backslashes for windows/linux compatibility
        $file = str_replace(['\\', '/'], [DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR], $file);
        
        if (file_exists($file)) {
            require $file;
        }
    });

    // Manual helper functions loading
    require_once dirname(__DIR__) . '/app/helpers/functions.php';
}

// 2. Initialize Session with security settings
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    ini_set('session.cookie_secure', 1);
}
session_start();

// 3. Set timezone
date_default_timezone_set(config('app.timezone', 'Asia/Dhaka'));

// 4. Set Dev Error Reporting
if (config('app.env') === 'development') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}

// 5. Initialize Router & Dispatch
$router = new \App\Router();

// Load routes definitions
require_once dirname(__DIR__) . '/routes/web.php';

// Resolve incoming URL (supports both Apache URL rewrite and PHP built-in server)
$url = $_GET['url'] ?? $_SERVER['REQUEST_URI'] ?? '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

$router->resolve($url, $method);
