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
echo "--- DOCTOR PROFILE TABLE ---\n";
print_r($db->query('DESCRIBE doctor_profile')->fetchAll(PDO::FETCH_ASSOC));
echo "--- USERS TABLE ---\n";
print_r($db->query('DESCRIBE users')->fetchAll(PDO::FETCH_ASSOC));
echo "--- CURRENT DOCTOR PROFILE ---\n";
print_r($db->query('SELECT * FROM doctor_profile LIMIT 1')->fetchAll(PDO::FETCH_ASSOC));
