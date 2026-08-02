<?php
$dbPath = __DIR__ . '/../sqlite.db';
$pdo = new PDO('sqlite:' . $dbPath);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// 1. Run the SQL file
$sql = file_get_contents(__DIR__ . '/add_phone_accounts.sql');
$pdo->exec($sql);
echo "Created phone_accounts table.\n";

// 2. Add columns to patients table
try {
    $pdo->exec("ALTER TABLE patients ADD COLUMN phone_account_id INTEGER DEFAULT NULL");
    echo "Added phone_account_id column.\n";
} catch (Exception $e) {
    echo "Column phone_account_id might already exist: " . $e->getMessage() . "\n";
}

try {
    $pdo->exec("ALTER TABLE patients ADD COLUMN patient_uid TEXT DEFAULT NULL");
    echo "Added patient_uid column.\n";
    $pdo->exec("CREATE UNIQUE INDEX IF NOT EXISTS idx_patients_uid ON patients(patient_uid)");
    echo "Created unique index on patient_uid.\n";
} catch (Exception $e) {
    echo "Column patient_uid might already exist: " . $e->getMessage() . "\n";
}

// 3. Migrate existing patients
$stmt = $pdo->query("SELECT DISTINCT phone FROM patients WHERE phone IS NOT NULL AND phone != ''");
$phones = $stmt->fetchAll(PDO::FETCH_COLUMN);

$defaultPasswordHash = password_hash('123456', PASSWORD_BCRYPT);
$now = date('Y-m-d H:i:s');

foreach ($phones as $phone) {
    // Check if phone account exists
    $stmt = $pdo->prepare("SELECT id FROM phone_accounts WHERE phone = ?");
    $stmt->execute([$phone]);
    $accountId = $stmt->fetchColumn();

    if (!$accountId) {
        $stmt = $pdo->prepare("INSERT INTO phone_accounts (phone, password_hash, created_at, updated_at) VALUES (?, ?, ?, ?)");
        $stmt->execute([$phone, $defaultPasswordHash, $now, $now]);
        $accountId = $pdo->lastInsertId();
    }

    // Update patients with this phone
    $stmt = $pdo->prepare("SELECT id, name FROM patients WHERE phone = ?");
    $stmt->execute([$phone]);
    $patients = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($patients as $patient) {
        $firstName = strtoupper(explode(' ', trim($patient['name']))[0]);
        $firstName = preg_replace('/[^A-Z]/', '', $firstName); // Keep only letters
        if (empty($firstName)) $firstName = 'UNKNOWN';
        
        $patientIdStr = str_pad($patient['id'], 4, '0', STR_PAD_LEFT);
        $uid = "P-{$firstName}-{$patientIdStr}";

        $updateStmt = $pdo->prepare("UPDATE patients SET phone_account_id = ?, patient_uid = ? WHERE id = ?");
        $updateStmt->execute([$accountId, $uid, $patient['id']]);
    }
}

echo "Migration completed successfully.\n";
