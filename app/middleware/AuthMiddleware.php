<?php

namespace App\Middleware;

class AuthMiddleware {
    public function handle(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            // Check if request is AJAX
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                header('Content-Type: application/json');
                http_response_code(401);
                echo json_encode(['error' => 'Unauthorized']);
                exit;
            }
            
            // Redirect to appropriate login
            $_SESSION['flash_error'] = 'Please sign in to access this page.';
            header('Location: ' . url('admin'));
            exit;
        }
    }
}
