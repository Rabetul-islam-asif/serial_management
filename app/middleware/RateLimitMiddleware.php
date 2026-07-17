<?php

namespace App\Middleware;

use App\Models\BaseModel;
use PDO;
use Exception;

class RateLimitMiddleware extends BaseModel {
    protected string $table = 'rate_limits';

    public function handle(string $endpoint = 'default'): void {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $now = time();
        
        $config = config("auth.rate_limits.{$endpoint}");
        if (!$config) {
            $config = ['attempts' => 60, 'window' => 60]; // fallback: 60 requests per minute
        }
        
        $maxAttempts = $config['attempts'];
        $window = $config['window'];
        $windowStart = $now - $window;

        try {
            // Clean up old rate limits first
            $this->execute("DELETE FROM {$this->table} WHERE window_start < :window_start", [
                'window_start' => date('Y-m-d H:i:s', $windowStart)
            ]);

            // Count attempts in current window
            $sql = "SELECT SUM(attempts) as total FROM {$this->table} WHERE ip_address = :ip AND endpoint = :endpoint AND window_start >= :window_start";
            $result = $this->query($sql, [
                'ip' => $ip,
                'endpoint' => $endpoint,
                'window_start' => date('Y-m-d H:i:s', $windowStart)
            ]);

            $totalAttempts = (int)($result[0]['total'] ?? 0);

            if ($totalAttempts >= $maxAttempts) {
                // Rate limit exceeded
                header('Retry-After: ' . $window);
                
                if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                    header('Content-Type: application/json');
                    http_response_code(429);
                    echo json_encode(['error' => 'Too Many Requests. Please try again later.']);
                    exit;
                }
                
                http_response_code(429);
                echo "<div style='font-family: sans-serif; text-align: center; margin-top: 100px; color: #1e293b;'>";
                echo "<h1>429 Too Many Requests</h1>";
                echo "<p>Please wait a few minutes before trying again.</p>";
                echo "</div>";
                exit;
            }

            // Log this request
            $this->create([
                'ip_address' => $ip,
                'endpoint' => $endpoint,
                'attempts' => 1,
                'window_start' => date('Y-m-d H:i:s', $now)
            ]);

        } catch (Exception $e) {
            // If the database schema isn't loaded yet or table doesn't exist, bypass rate limiting
            // so database migrations/seeding/initial setup can happen smoothly.
            return;
        }
    }
}
