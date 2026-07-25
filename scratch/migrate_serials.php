<?php
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/../app/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) return;
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    if (file_exists($file)) require $file;
});
require_once __DIR__ . '/../app/helpers/functions.php';

$userModel = new App\Models\User();
$db = $userModel->getDb();

// 1. Modify patient_type enum to include 'reference'
$db->exec("ALTER TABLE serials MODIFY COLUMN patient_type ENUM('normal','report','vip','emergency','followup','senior','pregnant','reference','custom') NOT NULL DEFAULT 'normal'");
echo "Updated patient_type ENUM to include 'reference'\n";

// 2. Add payment_status column if not exists
try {
    $db->exec("ALTER TABLE serials ADD COLUMN payment_status ENUM('paid','unpaid') NOT NULL DEFAULT 'unpaid' AFTER patient_type");
    echo "Added payment_status column to serials\n";
} catch (Exception $e) {
    echo "payment_status column already exists or info: " . $e->getMessage() . "\n";
}

// 3. Add bp, weight, pulse columns if not exist
try {
    $db->exec("ALTER TABLE serials ADD COLUMN bp VARCHAR(20) DEFAULT NULL AFTER notes, ADD COLUMN weight VARCHAR(20) DEFAULT NULL AFTER bp, ADD COLUMN pulse VARCHAR(20) DEFAULT NULL AFTER weight");
    echo "Added bp, weight, pulse columns to serials\n";
} catch (Exception $e) {
    echo "Vitals columns info: " . $e->getMessage() . "\n";
}

echo "Migration completed successfully!\n";
