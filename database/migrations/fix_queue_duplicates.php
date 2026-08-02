<?php

$db = new PDO('sqlite:' . __DIR__ . '/../sqlite.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo "Starting queue cleanup & migration...\n";

// 1. Add is_rollover column to serials if it doesn't exist
$cols = $db->query("PRAGMA table_info(serials)")->fetchAll(PDO::FETCH_ASSOC);
$colNames = array_column($cols, 'name');

if (!in_array('is_rollover', $colNames)) {
    $db->exec("ALTER TABLE serials ADD COLUMN is_rollover INTEGER DEFAULT 0");
    echo "[OK] Added is_rollover column to serials table.\n";
} else {
    echo "[INFO] is_rollover column already exists.\n";
}

// 2. Mark previous day serials as rolled over if they were created before today
$today = date('Y-m-d');
$stmt = $db->prepare("UPDATE serials SET is_rollover = 1 WHERE serial_date = :today AND token_number NOT LIKE :token_prefix");
$tokenPrefix = 'TK-' . date('ymd') . '%';
$stmt->execute(['today' => $today, 'token_prefix' => $tokenPrefix]);
echo "[OK] Updated rollover flags for past-date serials.\n";

// 3. Re-index serial_number and queue_position for today's serials sequentially
$stmtToday = $db->prepare("SELECT id FROM serials WHERE serial_date = :today AND status IN ('waiting', 'called', 'in_consultation', 'hold') ORDER BY queue_position ASC, id ASC");
$stmtToday->execute(['today' => $today]);
$serials = $stmtToday->fetchAll(PDO::FETCH_COLUMN);

$pos = 1;
$upStmt = $db->prepare("UPDATE serials SET queue_position = :pos, serial_number = :sn WHERE id = :id");
foreach ($serials as $id) {
    $upStmt->execute(['pos' => $pos, 'sn' => $pos, 'id' => $id]);
    $pos++;
}

echo "[OK] Re-indexed " . count($serials) . " serials for today sequentially (1.." . ($pos - 1) . ").\n";
echo "Migration complete.\n";
