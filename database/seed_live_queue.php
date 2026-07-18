<?php

/**
 * Seeder to populate today's queue with mock patient, appointment, and serial records.
 */

// Boot helper files
require_once dirname(__DIR__) . '/app/helpers/functions.php';

// Implement custom PSR-4 autoloader for App namespace
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = dirname(__DIR__) . '/app/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) return;
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

use App\Models\Patient;
use App\Models\Serial;
use App\Models\QueueEngine;

try {
    $serialModel = new Serial();
    $db = $serialModel->getDb();
    $date = date('Y-m-d');
    $chamberId = 1; // Metro Heart Chamber

    echo "Clearing today's serials and appointments for chamber {$chamberId}...\n";
    
    // Delete existing serials for today to prevent duplicates
    $db->prepare("DELETE FROM serials WHERE chamber_id = :chamber_id AND serial_date = :date")
       ->execute(['chamber_id' => $chamberId, 'date' => $date]);
       
    // Delete today's appointments for chamber 1
    $db->prepare("DELETE FROM appointments WHERE chamber_id = :chamber_id AND appointment_date = :date")
       ->execute(['chamber_id' => $chamberId, 'date' => $date]);

    // Patients data to seed/find
    $mockPatients = [
        ['name' => 'Abul Kalam', 'phone' => '01711111111', 'age' => 52, 'gender' => 'male', 'blood_group' => 'O+', 'address' => 'Mirpur, Dhaka'],
        ['name' => 'Rahima Begum', 'phone' => '01722222222', 'age' => 45, 'gender' => 'female', 'blood_group' => 'A+', 'address' => 'Dhanmondi, Dhaka'],
        ['name' => 'Kamal Hossain', 'phone' => '01733333333', 'age' => 29, 'gender' => 'male', 'blood_group' => 'B+', 'address' => 'Uttara, Dhaka'],
        ['name' => 'Salma Akter', 'phone' => '01744444444', 'age' => 34, 'gender' => 'female', 'blood_group' => 'AB+', 'address' => 'Gulshan, Dhaka'],
        ['name' => 'Karim Box', 'phone' => '01755555555', 'age' => 62, 'gender' => 'male', 'blood_group' => 'O-', 'address' => 'Badda, Dhaka'],
        // Include the demo patient phone so they have historical record as well
        ['name' => 'Demo Patient', 'phone' => '01712345678', 'age' => 30, 'gender' => 'male', 'blood_group' => 'B+', 'address' => 'Dhaka, Bangladesh']
    ];

    $patientModel = new Patient();
    $patientIds = [];

    foreach ($mockPatients as $pData) {
        // Find existing or insert new patient
        $existing = $patientModel->findBy('phone', $pData['phone']);
        if ($existing) {
            $patientIds[$pData['phone']] = $existing['id'];
            echo "Found patient: {$pData['name']} (ID: {$existing['id']})\n";
        } else {
            $id = $patientModel->create($pData);
            $patientIds[$pData['phone']] = $id;
            echo "Registered patient: {$pData['name']} (ID: {$id})\n";
        }
    }

    // Define 5 live queue serials for today
    $liveQueue = [
        ['phone' => '01711111111', 'type' => 'normal', 'status' => 'waiting', 'pos' => 1, 'serial' => 1],
        ['phone' => '01722222222', 'type' => 'report', 'status' => 'waiting', 'pos' => 2, 'serial' => 2],
        ['phone' => '01733333333', 'type' => 'vip', 'status' => 'waiting', 'pos' => 3, 'serial' => 3],
        ['phone' => '01744444444', 'type' => 'emergency', 'status' => 'waiting', 'pos' => 4, 'serial' => 4],
        ['phone' => '01755555555', 'type' => 'normal', 'status' => 'waiting', 'pos' => 5, 'serial' => 5],
    ];

    $db->beginTransaction();
    try {
        foreach ($liveQueue as $item) {
            $patientId = $patientIds[$item['phone']];
            
            // Insert Appointment
            $sqlApp = "INSERT INTO appointments (patient_id, chamber_id, appointment_date, appointment_type, status, notes, booked_by, created_at, updated_at) 
                       VALUES (:patient_id, :chamber_id, :date, :type, 'booked', 'Live Mock Appointment', 1, NOW(), NOW())";
            $stmtApp = $db->prepare($sqlApp);
            $stmtApp->execute([
                'patient_id' => $patientId,
                'chamber_id' => $chamberId,
                'date' => $date,
                'type' => $item['type'] === 'normal' ? 'walkin' : ($item['type'] === 'report' ? 'followup' : $item['type'])
            ]);
            $appointmentId = $db->lastInsertId();

            // Insert Serial
            $tokenNumber = "TK-" . date('ymd') . sprintf("%03d", $item['serial']);
            $serialModel->create([
                'appointment_id' => $appointmentId,
                'chamber_id' => $chamberId,
                'serial_date' => $date,
                'serial_number' => $item['serial'],
                'queue_position' => $item['pos'],
                'patient_type' => $item['type'],
                'status' => $item['status'],
                'token_number' => $tokenNumber,
                'notes' => 'Seeded live queue token'
            ]);
            
            echo "Seeded Serial #{$item['serial']} ({$tokenNumber}) for patient with phone {$item['phone']}\n";
        }
        $db->commit();
        
        // Run queue reordering rules
        $engine = new QueueEngine();
        $engine->reorderQueue($chamberId, $date);
        echo "Queue reordered successfully!\n";
        echo "Database seeding for today completed successfully.\n";

    } catch (Exception $e) {
        $db->rollBack();
        throw $e;
    }

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
