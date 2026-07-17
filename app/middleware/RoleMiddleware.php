<?php

namespace App\Middleware;

class RoleMiddleware {
    public function handle(string $roles): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $allowedRoles = explode(',', $roles);
        $userRole = $_SESSION['role'] ?? null;

        if (!in_array($userRole, $allowedRoles)) {
            // Check if request is AJAX
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                header('Content-Type: application/json');
                http_response_code(403);
                echo json_encode(['error' => 'Forbidden']);
                exit;
            }

            http_response_code(403);
            // Include custom 403 error view if available
            $errorFile = dirname(__DIR__) . "/views/errors/403.php";
            if (file_exists($errorFile)) {
                include $errorFile;
            } else {
                echo "<h1 style='font-family: sans-serif; text-align: center; margin-top: 100px; color: #1e293b;'>403 Forbidden: You do not have permission to access this resource.</h1>";
            }
            exit;
        }
    }
}
