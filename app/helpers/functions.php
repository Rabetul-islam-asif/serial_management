<?php

/**
 * Global helper functions for Doctor Serial Cloud
 */

if (!function_exists('env')) {
    function env(string $key, $default = null) {
        static $env = null;
        if ($env === null) {
            $envPath = dirname(__DIR__, 2) . '/.env';
            if (file_exists($envPath)) {
                $env = parse_ini_file($envPath);
            } else {
                $env = [];
            }
        }
        return $env[$key] ?? getenv($key) ?: $default;
    }
}

if (!function_exists('config')) {
    function config(string $key, $default = null) {
        static $configCache = [];
        
        $parts = explode('.', $key);
        $file = $parts[0];
        
        if (!isset($configCache[$file])) {
            $configPath = dirname(__DIR__) . "/config/{$file}.php";
            if (file_exists($configPath)) {
                $configCache[$file] = include $configPath;
            } else {
                $configCache[$file] = [];
            }
        }
        
        $value = $configCache[$file];
        for ($i = 1; $i < count($parts); $i++) {
            if (is_array($value) && isset($value[$parts[$i]])) {
                $value = $value[$parts[$i]];
            } else {
                return $default;
            }
        }
        
        return $value;
    }
}

if (!function_exists('url')) {
    function url(string $path = ''): string {
        $baseUrl = config('app.url', 'http://localhost/doctor-serial');
        return rtrim($baseUrl, '/') . '/' . ltrim($path, '/');
    }
}

if (!function_exists('asset')) {
    function asset(string $path): string {
        return url('assets/' . ltrim($path, '/'));
    }
}

if (!function_exists('esc')) {
    function esc(?string $string): string {
        if ($string === null) {
            return '';
        }
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('component')) {
    function component(string $name, array $props = []): void {
        $file = dirname(__DIR__) . "/views/components/{$name}.php";
        if (file_exists($file)) {
            extract($props);
            include $file;
        } else {
            trigger_error("Component [{$name}] not found at [{$file}]", E_USER_WARNING);
        }
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token(): string {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf_token'];
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string {
        return '<input type="hidden" name="_token" value="' . csrf_token() . '">';
    }
}

if (!function_exists('session')) {
    function session(?string $key = null, $default = null) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if ($key === null) {
            return $_SESSION;
        }
        return $_SESSION[$key] ?? $default;
    }
}

if (!function_exists('redirect')) {
    function redirect(string $path): void {
        header('Location: ' . url($path));
        exit;
    }
}

if (!function_exists('dd')) {
    function dd(...$vars): void {
        echo '<pre style="background: #111827; color: #F3F4F6; padding: 20px; border-radius: 8px; font-family: monospace; font-size: 14px; overflow: auto; line-height: 1.5;">';
        foreach ($vars as $var) {
            var_dump($var);
        }
        echo '</pre>';
        exit;
    }
}

if (!function_exists('get_doctor_photo')) {
    function get_doctor_photo(?string $photo = null): string {
        if (empty($photo)) {
            return asset('images/doctor_portrait.jpg');
        }
        if (str_starts_with($photo, 'http://') || str_starts_with($photo, 'https://')) {
            return $photo;
        }
        if (str_starts_with($photo, 'uploads/') || str_starts_with($photo, 'images/')) {
            return asset($photo);
        }
        $publicDir = dirname(__DIR__, 2) . '/public';
        if (file_exists($publicDir . '/uploads/photos/' . $photo)) {
            return asset('uploads/photos/' . $photo);
        }
        if (file_exists($publicDir . '/assets/images/' . $photo)) {
            return asset('images/' . $photo);
        }
        return asset('images/' . ltrim($photo, '/'));
    }
}

