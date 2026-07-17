<?php

namespace App\Middleware;

class CsrfMiddleware {
    public function handle(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        
        // Only validate modifying verbs
        if (in_array($method, ['POST', 'PUT', 'DELETE'])) {
            $token = $_POST['_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
            
            if (!$token || !isset($_SESSION['_csrf_token']) || !hash_equals($_SESSION['_csrf_token'], $token)) {
                if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                    header('Content-Type: application/json');
                    http_response_code(419);
                    echo json_encode(['error' => 'CSRF Token Mismatch']);
                    exit;
                }
                
                http_response_code(419);
                echo "<div style='font-family: sans-serif; text-align: center; margin-top: 100px; color: #1e293b;'>";
                echo "<h1>419 CSRF Token Expired</h1>";
                echo "<p>Please go back, refresh the page, and try again.</p>";
                echo "</div>";
                exit;
            }
        }
    }
}
