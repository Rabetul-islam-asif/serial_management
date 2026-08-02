<?php

$sql = file_get_contents(__DIR__ . '/cpanel_full_import.sql');

// Strip MariaDB/MySQL specific comments and commands
$sql = preg_replace('/^\/\*!.*?\*\/;/m', '', $sql);
$sql = preg_replace('/\/\*!.*?\*\//s', '', $sql);
$sql = preg_replace('/LOCK TABLES `.*?` WRITE;/i', '', $sql);
$sql = preg_replace('/UNLOCK TABLES;/i', '', $sql);

// Remove column COMMENT '...' and JSON check constraints
$sql = preg_replace("/COMMENT\s+'[^']*'/i", '', $sql);
$sql = preg_replace('/CHARACTER SET utf8mb4 COLLATE utf8mb4_bin/i', '', $sql);
$sql = preg_replace('/CHECK\s*\(\s*json_valid\(`.*?`\)\s*\)/i', '', $sql);

// Convert MySQL enum(...) to TEXT
$sql = preg_replace('/enum\s*\([^)]+\)/is', 'TEXT', $sql);

// Convert MySQL data types & table attributes for SQLite (using word boundaries for types)
$sql = preg_replace('/`id` bigint\(\d+\) unsigned NOT NULL AUTO_INCREMENT/i', '`id` INTEGER PRIMARY KEY AUTOINCREMENT', $sql);
$sql = preg_replace('/`id` int\(\d+\) unsigned NOT NULL AUTO_INCREMENT/i', '`id` INTEGER PRIMARY KEY AUTOINCREMENT', $sql);
$sql = preg_replace('/\bbigint\(\d+\) unsigned\b/i', 'INTEGER', $sql);
$sql = preg_replace('/\bint\(\d+\) unsigned\b/i', 'INTEGER', $sql);
$sql = preg_replace('/\bint\(\d+\)\b/i', 'INTEGER', $sql);
$sql = preg_replace('/\btinyint\(\d+\)\b/i', 'INTEGER', $sql);
$sql = preg_replace('/DEFAULT current_timestamp\(\)/i', 'DEFAULT CURRENT_TIMESTAMP', $sql);
$sql = preg_replace('/\bvarchar\(\d+\)\b/i', 'TEXT', $sql);
$sql = preg_replace('/\blongtext\b/i', 'TEXT', $sql);
$sql = preg_replace('/\bdatetime\b/i', 'TEXT', $sql);
$sql = preg_replace('/\bdate\b/i', 'TEXT', $sql);
$sql = preg_replace('/ENGINE=InnoDB.*?;/i', ';', $sql);

// Process line by line to strip KEY, UNIQUE KEY, CONSTRAINT inside CREATE TABLE
$lines = explode("\n", $sql);
$cleanLines = [];
foreach ($lines as $line) {
    $trimmed = trim($line);
    if (preg_match('/^(UNIQUE\s+)?(KEY|INDEX)\s+/i', $trimmed) ||
        preg_match('/^CONSTRAINT\s+/i', $trimmed) ||
        preg_match('/^PRIMARY KEY \(`id`\)/i', $trimmed) ||
        preg_match('/^\/\*/', $trimmed)) {
        continue;
    }
    $cleanLines[] = $line;
}
$cleanSql = implode("\n", $cleanLines);

// Remove trailing commas before closing parenthesis
$cleanSql = preg_replace('/,(\s*\))/s', '$1', $cleanSql);

$dbFile = __DIR__ . '/sqlite.db';
if (file_exists($dbFile)) {
    unlink($dbFile);
}

$pdo = new PDO('sqlite:' . $dbFile);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->exec('PRAGMA foreign_keys = OFF;');

$statements = preg_split('/;[ \t]*[\r\n]+/', $cleanSql);
$success = 0;
$errors = 0;

foreach ($statements as $stmt) {
    $stmt = trim($stmt);
    if (!empty($stmt)) {
        try {
            $pdo->exec($stmt);
            $success++;
        } catch (Exception $e) {
            $errors++;
            echo "Error: " . substr(trim($stmt), 0, 80) . " -> " . $e->getMessage() . "\n";
        }
    }
}

echo "SQLite DB initialized: {$success} statements executed successfully, {$errors} errors.\n";
$tables = $pdo->query("SELECT name FROM sqlite_master WHERE type='table'")->fetchAll(PDO::FETCH_COLUMN);
echo "Tables created (" . count($tables) . "): " . implode(', ', $tables) . "\n";
