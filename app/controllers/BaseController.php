<?php

namespace App\Controllers;

abstract class BaseController {
    
    /**
     * Render a view file inside a specified layout
     */
    protected function view(string $template, array $data = [], string $layout = 'app'): void {
        // Prepare view path
        $viewFile = dirname(__DIR__) . "/views/{$template}.php";
        if (!file_exists($viewFile)) {
            throw new \Exception("View template [{$template}] not found at [{$viewFile}]");
        }

        // Extract variables to the view context
        extract($data);

        // Capture content of the template
        ob_start();
        include $viewFile;
        $content = ob_get_clean();

        // Render layout containing the content
        $layoutFile = dirname(__DIR__) . "/views/layouts/{$layout}.php";
        if (file_exists($layoutFile)) {
            include $layoutFile;
        } else {
            // Fallback if layout is missing
            echo $content;
        }
    }

    /**
     * Output a JSON API response
     */
    protected function json($data, int $statusCode = 200): void {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }

    /**
     * Redirect to a specific URL path
     */
    protected function redirect(string $path): void {
        header('Location: ' . url($path));
        exit;
    }

    /**
     * Send errors back to session and redirect
     */
    protected function redirectWithError(string $path, string $error): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['flash_error'] = $error;
        $this->redirect($path);
    }

    /**
     * Send success back to session and redirect
     */
    protected function redirectWithSuccess(string $path, string $message): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['flash_success'] = $message;
        $this->redirect($path);
    }

    /**
     * Check if request is AJAX
     */
    protected function isAjax(): bool {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
}
