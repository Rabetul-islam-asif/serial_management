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

echo "--- VISITS TABLE ---\n";
try { print_r($db->query("DESCRIBE visits")->fetchAll()); } catch (Exception $e) { echo $e->getMessage(); }

echo "\n--- APPOINTMENTS TABLE ---\n";
try { print_r($db->query("DESCRIBE appointments")->fetchAll()); } catch (Exception $e) { echo $e->getMessage(); }
