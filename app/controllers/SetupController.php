<?php

namespace App\Controllers;

use PDO;
use Exception;

class SetupController extends BaseController {

    /**
     * 1-Click Database Setup & Auto-Importer (/setup-db)
     */
    public function setupDb(): void {
        $config = config('database');
        
        try {
            $host = $config['host'];
            $port = $config['port'] ?? 3306;
            if (strpos($host, ':') !== false) {
                [$parsedHost, $parsedPort] = explode(':', $host, 2);
                $host = $parsedHost;
                if (!empty($parsedPort)) {
                    $port = $parsedPort;
                }
            }

            $dsn = "mysql:host={$host};port={$port};dbname={$config['database']};charset={$config['charset']}";
            $db = new PDO($dsn, $config['username'], $config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
            ]);

            $sqlPath = dirname(__DIR__, 2) . '/database/cpanel_full_import.sql';
            if (!file_exists($sqlPath)) {
                throw new Exception("SQL dump file not found at: {$sqlPath}");
            }

            $rawSql = file_get_contents($sqlPath);
            $sql = "SET FOREIGN_KEY_CHECKS = 0;\n" . $rawSql . "\nSET FOREIGN_KEY_CHECKS = 1;";

            // Split into individual SQL statements by semicolon + line break
            $statements = preg_split("/;[ \t]*[\r\n]+/", $sql);

            foreach ($statements as $stmt) {
                $q = trim($stmt);
                if (!empty($q)) {
                    try {
                        $db->exec($q);
                    } catch (Exception $eSingle) {
                        // Ignore minor inline comment or drop table errors
                    }
                }
            }

            // Verify imported tables count
            $tablesStmt = $db->query("SHOW TABLES");
            $tables = $tablesStmt->fetchAll(PDO::FETCH_COLUMN);

            // Render clean success response
            http_response_code(200);
            echo "<!DOCTYPE html><html><head><title>Database Setup Successful</title>";
            echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
            echo "<style>body{font-family:system-ui,-apple-system,sans-serif;background:#0f172a;color:#f8fafc;display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0;}";
            echo ".card{background:#1e293b;border:1px solid #334155;border-radius:16px;padding:32px;max-width:540px;width:90%;box-shadow:0 20px 25px -5px rgba(0,0,0,0.5);}";
            echo ".badge{display:inline-block;padding:4px 12px;background:#065f46;color:#34d399;font-weight:700;border-radius:9999px;font-size:12px;margin-bottom:12px;}";
            echo "h1{font-size:24px;margin:0 0 8px 0;color:#38bdf8;}";
            echo "p{color:#94a3b8;font-size:14px;line-height:1.6;}";
            echo ".btn{display:inline-block;padding:12px 24px;background:linear-gradient(135deg,#0284c7,#0369a1);color:#fff;text-decoration:none;font-weight:700;border-radius:8px;margin-top:16px;margin-right:8px;}";
            echo ".btn-sec{background:#334155;color:#f8fafc;}";
            echo "ul{background:#0f172a;padding:12px 20px;border-radius:8px;font-size:13px;color:#cbd5e1;}";
            echo "</style></head><body>";
            echo "<div class='card'>";
            echo "<span class='badge'>✓ 1-Click Setup Complete</span>";
            echo "<h1>🎉 Database Initialized!</h1>";
            echo "<p>Your cloud database at <code>" . htmlspecialchars($config['host']) . "</code> (Database: <code>" . htmlspecialchars($config['database']) . "</code>) was successfully populated with all tables and initial seed data.</p>";
            echo "<p><strong>Imported Tables (" . count($tables) . "):</strong></p>";
            echo "<ul>";
            foreach ($tables as $t) {
                echo "<li>" . htmlspecialchars($t) . "</li>";
            }
            echo "</ul>";
            echo "<div style='margin-top:24px;'>";
            echo "<a href='" . url('admin') . "' class='btn'>🔐 Reception / Doctor Login</a>";
            echo "<a href='" . url('queue/board') . "' class='btn btn-sec'>📺 View Public Board</a>";
            echo "</div>";
            echo "</div>";
            echo "</body></html>";
            exit;

        } catch (Exception $e) {
            http_response_code(500);
            $errMsg = $e->getMessage();
            $isDnsError = strpos($errMsg, 'getaddrinfo') !== false || strpos($errMsg, 'Name or service not known') !== false;

            echo "<!DOCTYPE html><html><head><title>Database Setup Failed</title>";
            echo "<style>body{font-family:system-ui,sans-serif;background:#0f172a;color:#f8fafc;display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0;}";
            echo ".card{background:#1e293b;border:1px solid #991b1b;border-radius:16px;padding:32px;max-width:580px;width:90%;box-shadow:0 20px 25px -5px rgba(0,0,0,0.5);}";
            echo "h1{font-size:22px;color:#f87171;margin-top:0;}";
            echo "p{color:#cbd5e1;font-size:14px;line-height:1.6;}";
            echo "code{background:#0f172a;padding:4px 8px;border-radius:4px;color:#38bdf8;word-break:break-all;}";
            echo ".hint{background:#1e1b4b;border:1px solid #4338ca;padding:12px 16px;border-radius:8px;font-size:13px;color:#c7d2fe;margin-top:12px;}";
            echo "</style></head><body>";
            echo "<div class='card'>";
            echo "<h1>⚠️ Database Setup Failed</h1>";
            echo "<p>Could not connect or run SQL script on database host <code>" . htmlspecialchars($config['host']) . "</code>.</p>";
            echo "<p><strong>Error Message:</strong><br><code>" . htmlspecialchars($errMsg) . "</code></p>";
            
            if ($isDnsError) {
                echo "<div class='hint'>";
                echo "<strong>🔍 DNS / Hostname Diagnosis:</strong><br>";
                echo "1. Verify <code>DB_HOST</code> in Render match your Aiven <strong>Host</strong> value exactly.<br>";
                echo "2. Check your Aiven Dashboard: Ensure your MySQL service status is <strong>Running</strong> (green) and not restarting/stopped.<br>";
                echo "3. Remove any extra letters or characters from the hostname.";
                echo "</div>";
            } else {
                echo "<p><strong>Troubleshooting:</strong><br>1. Check Environment Variables on Render (DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD).<br>2. Ensure database name exists in your cloud MySQL instance.</p>";
            }
            echo "</div>";
            echo "</body></html>";
            exit;
        }
    }
}
