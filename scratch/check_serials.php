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

$serialModel = new App\Models\Serial();
$queue = $serialModel->getQueue(1, date('Y-m-d'));
echo "--- TODAY QUEUE COUNT ---: " . count($queue) . "\n";

$db = $serialModel->getDb();
$serials = $db->query("SELECT serial_date, status, COUNT(*) as cnt FROM serials GROUP BY serial_date, status")->fetchAll();
print_r($serials);
