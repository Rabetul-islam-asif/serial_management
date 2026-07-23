<?php

namespace App\Controllers;

use App\Models\Serial;
use App\Models\QueueEngine;
use App\Models\Patient;
use App\Models\BaseModel;
use Exception;

class SerialController extends BaseController {

    /**
     * Create Serial (Walk-in or Appointment entry)
     */
    public function createSerial(): void {
        $patientId = intval($_POST['patient_id'] ?? 0);
        $chamberId = intval($_POST['chamber_id'] ?? 1); // default chamber 1
        $patientType = $_POST['patient_type'] ?? 'normal';
        $notes = trim($_POST['notes'] ?? '');

        if ($patientId <= 0) {
            $this->redirectWithError('dashboard', 'Invalid Patient selection.');
        }

        // Parse Vitals & Health Conditions
        $vitals = [];
        if (!empty($_POST['bp'])) $vitals[] = 'BP: ' . trim($_POST['bp']);
        if (!empty($_POST['weight'])) $vitals[] = 'Weight: ' . trim($_POST['weight']) . 'kg';
        if (!empty($_POST['pulse'])) $vitals[] = 'Pulse: ' . trim($_POST['pulse']) . 'bpm';
        if (!empty($_POST['temp'])) $vitals[] = 'Temp: ' . trim($_POST['temp']) . '°F';
        
        $vitalsStr = implode(' | ', $vitals);
        if (!empty($vitalsStr)) {
            $notes = empty($notes) ? $vitalsStr : $vitalsStr . ' - ' . $notes;
        }

        // Handle scanned prescription file uploads
        $uploadedPath = null;
        if (isset($_FILES['prescription_file']) && $_FILES['prescription_file']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['prescription_file']['tmp_name'];
            $fileName = $_FILES['prescription_file']['name'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            
            $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png'];
            if (in_array($fileExtension, $allowedExtensions)) {
                $uploadDir = dirname(__DIR__, 2) . '/public/uploads/prescriptions/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                $newFileName = 'uploaded_' . uniqid() . '.' . $fileExtension;
                $destPath = $uploadDir . $newFileName;
                
                if (move_uploaded_file($fileTmpPath, $destPath)) {
                    $uploadedPath = 'uploads/prescriptions/' . $newFileName;
                }
            }
        }

        $serialModel = new Serial();
        $date = date('Y-m-d');

        $db = $serialModel->getDb();
        $db->beginTransaction();

        try {
            // 1. Create Appointment
            $sqlApp = "INSERT INTO appointments (patient_id, chamber_id, appointment_date, appointment_type, status, notes, booked_by, created_at, updated_at) 
                       VALUES (:patient_id, :chamber_id, :date, :type, 'booked', :notes, :booked_by, NOW(), NOW())";
            $stmtApp = $db->prepare($sqlApp);
            $stmtApp->execute([
                'patient_id' => $patientId,
                'chamber_id' => $chamberId,
                'date' => $date,
                'type' => $patientType,
                'notes' => $notes,
                'booked_by' => session('user_id')
            ]);
            $appointmentId = $db->lastInsertId();

            // 2. Determine Serial Number and Queue Position
            $maxSerial = $serialModel->getMaxSerialNumber($chamberId, $date);
            $nextSerial = $maxSerial + 1;

            $maxPosition = $serialModel->getMaxQueuePosition($chamberId, $date);
            $nextPosition = $maxPosition + 1;

            $tokenNumber = "TK-" . date('ymd') . sprintf("%03d", $nextSerial);

            // 3. Create Serial Record
            $serialModel->create([
                'appointment_id' => $appointmentId,
                'chamber_id' => $chamberId,
                'serial_date' => $date,
                'serial_number' => $nextSerial,
                'queue_position' => $nextPosition,
                'patient_type' => $patientType,
                'status' => 'waiting',
                'token_number' => $tokenNumber,
                'notes' => $notes
            ]);

            // 4. Record Scanned/Uploaded Prescription if present
            if ($uploadedPath !== null) {
                // Create a Visit entry
                $sqlVisit = "INSERT INTO visits (patient_id, chamber_id, visit_date, chief_complaint, diagnosis, status, created_at, updated_at) 
                             VALUES (:patient_id, :chamber_id, :date, 'Historical Records Upload', 'Document Scan Upload', 'completed', NOW(), NOW())";
                $stmtVisit = $db->prepare($sqlVisit);
                $stmtVisit->execute([
                    'patient_id' => $patientId,
                    'chamber_id' => $chamberId,
                    'date' => $date
                ]);
                $visitId = $db->lastInsertId();

                // Create a Prescription entry
                $prescNo = 'PRES-' . date('ymd') . sprintf("%04d", rand(1000, 9999));
                $sqlPresc = "INSERT INTO prescriptions (visit_id, patient_id, prescription_number, rx_date, pdf_path, created_at, updated_at) 
                             VALUES (:visit_id, :patient_id, :presc_no, :date, :pdf_path, NOW(), NOW())";
                $stmtPresc = $db->prepare($sqlPresc);
                $stmtPresc->execute([
                    'visit_id' => $visitId,
                    'patient_id' => $patientId,
                    'presc_no' => $prescNo,
                    'date' => $date,
                    'pdf_path' => $uploadedPath
                ]);
            }

            $db->commit();

            // 5. Trigger Queue Engine Reordering
            $engine = new QueueEngine();
            $engine->reorderQueue($chamberId, $date);

            $this->redirectWithSuccess('reception/queue', "Token generated successfully: {$tokenNumber} (Serial #{$nextSerial})");

        } catch (Exception $e) {
            $db->rollBack();
            $this->redirectWithError('reception/queue', 'Failed to generate token: ' . $e->getMessage());
        }
    }

    /**
     * Book Online Appointment (Public Patient flow with max capacity check)
     */
    public function bookOnlineAppointment(): void {
        $chamberId = intval($_POST['chamber_id'] ?? 1);
        $name = trim($_POST['name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');

        if (empty($name) || empty($phone)) {
            $this->redirectWithError('/', 'Please fill in all booking fields.');
        }

        // Basic sanity check for phone format
        if (!preg_match('/^[0-9]{11,15}$/', $phone)) {
            $this->redirectWithError('/', 'Please enter a valid mobile number (11-15 digits).');
        }

        $serialModel = new Serial();
        $date = date('Y-m-d');
        $db = $serialModel->getDb();

        // 1. Check current online appointment capacity
        $settingsStmt = $db->prepare("SELECT setting_value FROM queue_settings WHERE chamber_id = :chamber_id AND setting_key = 'max_online_appointments'");
        $settingsStmt->execute(['chamber_id' => $chamberId]);
        $maxOnline = $settingsStmt->fetchColumn();
        if ($maxOnline === false) {
            $maxOnline = 20; // default fallback
        } else {
            $maxOnline = intval($maxOnline);
        }

        // Count how many online appointments already booked for today
        $countStmt = $db->prepare("SELECT COUNT(*) FROM appointments WHERE chamber_id = :chamber_id AND appointment_date = :date AND status != 'cancelled'");
        $countStmt->execute(['chamber_id' => $chamberId, 'date' => $date]);
        $currentCount = intval($countStmt->fetchColumn());

        if ($currentCount >= $maxOnline) {
            $this->redirectWithError('/', "Registration full! Today's online quota of {$maxOnline} appointments has been filled. Please visit the clinic directly for walk-in slots.");
        }

        $db->beginTransaction();
        try {
            // 2. Find or create patient record
            $patientStmt = $db->prepare("SELECT id FROM patients WHERE phone = :phone AND deleted_at IS NULL");
            $patientStmt->execute(['phone' => $phone]);
            $patientId = $patientStmt->fetchColumn();

            if (!$patientId) {
                // Register new patient card
                $insPatient = $db->prepare("INSERT INTO patients (name, phone, age, gender, created_at, updated_at) VALUES (:name, :phone, 30, 'other', NOW(), NOW())");
                $insPatient->execute(['name' => $name, 'phone' => $phone]);
                $patientId = $db->lastInsertId();
            }

            // 3. Create Appointment
            $sqlApp = "INSERT INTO appointments (patient_id, chamber_id, appointment_date, appointment_type, status, booked_by, created_at, updated_at) 
                       VALUES (:patient_id, :chamber_id, :date, 'normal', 'booked', 0, NOW(), NOW())";
            $stmtApp = $db->prepare($sqlApp);
            $stmtApp->execute([
                'patient_id' => $patientId,
                'chamber_id' => $chamberId,
                'date' => $date
            ]);
            $appointmentId = $db->lastInsertId();

            // 4. Generate Token & position
            $maxSerial = $serialModel->getMaxSerialNumber($chamberId, $date);
            $nextSerial = $maxSerial + 1;

            $maxPosition = $serialModel->getMaxQueuePosition($chamberId, $date);
            $nextPosition = $maxPosition + 1;

            $tokenNumber = "TK-" . date('ymd') . sprintf("%03d", $nextSerial);

            // 5. Create Serial Record
            $serialModel->create([
                'appointment_id' => $appointmentId,
                'chamber_id' => $chamberId,
                'serial_date' => $date,
                'serial_number' => $nextSerial,
                'queue_position' => $nextPosition,
                'patient_type' => 'normal',
                'status' => 'waiting',
                'token_number' => $tokenNumber,
                'notes' => 'Online Booking'
            ]);

            $db->commit();

            // 6. Trigger reordering algorithm
            $engine = new QueueEngine();
            $engine->reorderQueue($chamberId, $date);

            $this->redirectWithSuccess('/', "Appointment confirmed! Your Token is: {$tokenNumber} (Queue Serial: #{$nextSerial}). Please arrive on time.");

        } catch (Exception $e) {
            $db->rollBack();
            $this->redirectWithError('/', 'Booking failed: ' . $e->getMessage());
        }
    }

    /**
     * Call Patient
     */
    public function callPatient(): void {
        $id = intval($_POST['id'] ?? 0);
        if ($id <= 0) $this->json(['error' => 'Invalid ID'], 400);

        $serialModel = new Serial();
        $serial = $serialModel->find($id);

        if (!$serial) $this->json(['error' => 'Serial not found'], 404);

        $db = $serialModel->getDb();
        $db->beginTransaction();

        try {
            // Set all other active called patients back to waiting
            $sqlReset = "UPDATE serials SET status = 'waiting' 
                         WHERE chamber_id = :chamber_id 
                           AND serial_date = :date 
                           AND status = 'called'";
            $stmtReset = $db->prepare($sqlReset);
            $stmtReset->execute([
                'chamber_id' => $serial['chamber_id'],
                'date' => $serial['serial_date']
            ]);

            // Update this serial
            $serialModel->update($id, [
                'status' => 'called',
                'called_at' => date('Y-m-d H:i:s')
            ]);

            $db->commit();

            // Run reorder
            $engine = new QueueEngine();
            $engine->reorderQueue($serial['chamber_id'], $serial['serial_date']);

            $this->json(['success' => true]);

        } catch (Exception $e) {
            $db->rollBack();
            $this->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Complete Visit
     */
    public function completePatient(): void {
        $id = intval($_POST['id'] ?? 0);
        if ($id <= 0) $this->json(['error' => 'Invalid ID'], 400);

        $serialModel = new Serial();
        $serial = $serialModel->find($id);

        if (!$serial) $this->json(['error' => 'Serial not found'], 404);

        $db = $serialModel->getDb();
        $db->beginTransaction();

        try {
            // 1. Update Serial status
            $serialModel->update($id, [
                'status' => 'completed',
                'completed_at' => date('Y-m-d H:i:s')
            ]);

            // 2. Update Appointment status
            $sqlApp = "UPDATE appointments SET status = 'completed', updated_at = NOW() WHERE id = :app_id";
            $stmtApp = $db->prepare($sqlApp);
            $stmtApp->execute(['app_id' => $serial['appointment_id']]);

            $db->commit();

            // Run reorder
            $engine = new QueueEngine();
            $engine->reorderQueue($serial['chamber_id'], $serial['serial_date']);

            $this->json(['success' => true]);

        } catch (Exception $e) {
            $db->rollBack();
            $this->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Missed Patient
     */
    public function missPatient(): void {
        $id = intval($_POST['id'] ?? 0);
        if ($id <= 0) $this->json(['error' => 'Invalid ID'], 400);

        $serialModel = new Serial();
        $serial = $serialModel->find($id);

        if (!$serial) $this->json(['error' => 'Serial not found'], 404);

        $serialModel->update($id, [
            'status' => 'missed'
        ]);

        $engine = new QueueEngine();
        $engine->reorderQueue($serial['chamber_id'], $serial['serial_date']);

        $this->json(['success' => true]);
    }

    /**
     * Rejoin Missed Patient
     */
    public function rejoinPatient(): void {
        $id = intval($_POST['id'] ?? 0);
        $afterN = intval($_POST['rejoin_after'] ?? 3); // Default rejoin after 3 patients

        if ($id <= 0) $this->json(['error' => 'Invalid ID'], 400);

        $serialModel = new Serial();
        $serial = $serialModel->find($id);

        if (!$serial) $this->json(['error' => 'Serial not found'], 404);

        $serialModel->update($id, [
            'status' => 'waiting',
            'is_rejoined' => 1,
            'missed_rejoin_after' => $afterN,
            'original_position' => $serial['queue_position']
        ]);

        $engine = new QueueEngine();
        $engine->reorderQueue($serial['chamber_id'], $serial['serial_date']);

        $this->json(['success' => true]);
    }

    /**
     * Hold Patient
     */
    public function holdPatient(): void {
        $id = intval($_POST['id'] ?? 0);
        $reason = trim($_POST['reason'] ?? 'Awaiting Reports');

        if ($id <= 0) $this->json(['error' => 'Invalid ID'], 400);

        $serialModel = new Serial();
        $serial = $serialModel->find($id);

        if (!$serial) $this->json(['error' => 'Serial not found'], 404);

        $serialModel->update($id, [
            'status' => 'hold',
            'hold_reason' => $reason
        ]);

        $engine = new QueueEngine();
        $engine->reorderQueue($serial['chamber_id'], $serial['serial_date']);

        $this->json(['success' => true]);
    }

    /**
     * Drag and Drop Reordering
     */
    public function dragDrop(): void {
        $positions = $_POST['positions'] ?? []; // Array of {id, position}
        if (empty($positions)) $this->json(['error' => 'Invalid positions array'], 400);

        $serialModel = new Serial();
        $db = $serialModel->getDb();
        $db->beginTransaction();

        try {
            foreach ($positions as $item) {
                $sql = "UPDATE serials SET queue_position = :pos WHERE id = :id";
                $stmt = $db->prepare($sql);
                $stmt->execute([
                    'pos' => intval($item['position']),
                    'id' => intval($item['id'])
                ]);
            }
            $db->commit();
            $this->json(['success' => true]);
        } catch (Exception $e) {
            $db->rollBack();
            $this->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Upload or Edit Patient Prescription Scan (Receptionist flow)
     */
    public function uploadPrescription(): void {
        $serialId = intval($_POST['serial_id'] ?? 0);
        
        if ($serialId <= 0) {
            $this->redirectWithError('reception/queue', 'Invalid queue slot selection.');
        }

        $serialModel = new Serial();
        $serial = $serialModel->find($serialId);
        if (!$serial) {
            $this->redirectWithError('reception/queue', 'Queue slot not found.');
        }

        $patientId = null;
        if (!empty($serial['appointment_id'])) {
            $db = $serialModel->getDb();
            $stmtApp = $db->prepare("SELECT patient_id FROM appointments WHERE id = :id");
            $stmtApp->execute(['id' => $serial['appointment_id']]);
            $patientId = $stmtApp->fetchColumn();
        }

        if (!$patientId) {
            $this->redirectWithError('reception/queue', 'Patient reference not found.');
        }

        // Handle uploaded file
        $uploadedPath = null;
        if (isset($_FILES['prescription_file']) && $_FILES['prescription_file']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['prescription_file']['tmp_name'];
            $fileName = $_FILES['prescription_file']['name'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            
            $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png'];
            if (in_array($fileExtension, $allowedExtensions)) {
                $uploadDir = dirname(__DIR__, 2) . '/public/uploads/prescriptions/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                $newFileName = 'uploaded_' . uniqid() . '.' . $fileExtension;
                $destPath = $uploadDir . $newFileName;
                
                if (move_uploaded_file($fileTmpPath, $destPath)) {
                    $uploadedPath = 'uploads/prescriptions/' . $newFileName;
                }
            }
        }

        if (!$uploadedPath) {
            $this->redirectWithError('reception/queue', 'Please select a valid PDF or Image file.');
        }

        $db = $serialModel->getDb();
        $db->beginTransaction();

        try {
            // Check if a visit already exists for this serial
            $visitStmt = $db->prepare("SELECT id FROM visits WHERE serial_id = :serial_id");
            $visitStmt->execute(['serial_id' => $serialId]);
            $visitId = $visitStmt->fetchColumn();

            if (!$visitId) {
                // Insert a new visit
                $sqlVisit = "INSERT INTO visits (patient_id, serial_id, chamber_id, visit_date, chief_complaint, diagnosis, status, created_at, updated_at) 
                             VALUES (:patient_id, :serial_id, :chamber_id, :date, 'Historical Records Upload', 'Document Scan Upload', 'completed', NOW(), NOW())";
                $stmtVisit = $db->prepare($sqlVisit);
                $stmtVisit->execute([
                    'patient_id' => $patientId,
                    'serial_id' => $serialId,
                    'chamber_id' => $serial['chamber_id'],
                    'date' => $serial['serial_date']
                ]);
                $visitId = $db->lastInsertId();
            }

            // Check if a prescription already exists for this visit
            $prescStmt = $db->prepare("SELECT id, pdf_path FROM prescriptions WHERE visit_id = :visit_id");
            $prescStmt->execute(['visit_id' => $visitId]);
            $existingPresc = $prescStmt->fetch();

            if ($existingPresc) {
                // Delete previous file if possible
                $oldFile = dirname(__DIR__, 2) . '/public/' . $existingPresc['pdf_path'];
                if (file_exists($oldFile) && is_file($oldFile)) {
                    @unlink($oldFile);
                }

                // Update existing record
                $upStmt = $db->prepare("UPDATE prescriptions SET pdf_path = :pdf_path, updated_at = NOW() WHERE id = :id");
                $upStmt->execute(['pdf_path' => $uploadedPath, 'id' => $existingPresc['id']]);
            } else {
                // Insert a new prescription
                $prescNo = 'PRES-' . date('ymd') . sprintf("%04d", rand(1000, 9999));
                $sqlPresc = "INSERT INTO prescriptions (visit_id, patient_id, prescription_number, rx_date, pdf_path, created_at, updated_at) 
                             VALUES (:visit_id, :patient_id, :presc_no, :date, :pdf_path, NOW(), NOW())";
                $stmtPresc = $db->prepare($sqlPresc);
                $stmtPresc->execute([
                    'visit_id' => $visitId,
                    'patient_id' => $patientId,
                    'presc_no' => $prescNo,
                    'date' => $serial['serial_date'],
                    'pdf_path' => $uploadedPath
                ]);
            }

            $db->commit();
            $this->redirectWithSuccess('reception/queue', 'Prescription file uploaded/updated successfully.');

        } catch (Exception $e) {
            $db->rollBack();
            $this->redirectWithError('reception/queue', 'Database error: ' . $e->getMessage());
        }
    }

    /**
     * Call Next Patient in Line (1-Click Top Banner Action)
     */
    public function callNextPatient(): void {
        $chamberId = intval($_POST['chamber_id'] ?? 1);
        $date = date('Y-m-d');

        $serialModel = new Serial();
        $next = $serialModel->getNextWaiting($chamberId, $date);

        if (!$next) {
            $this->json(['error' => 'No waiting patients in queue today.'], 404);
            return;
        }

        $_POST['id'] = $next['id'];
        $this->callPatient();
    }

    /**
     * Book Manual Advance Appointment (Receptionist / Admin flow for future dates)
     */
    public function bookManualAppointment(): void {
        $patientId = intval($_POST['patient_id'] ?? 0);
        $chamberId = intval($_POST['chamber_id'] ?? 1);
        $appointmentDate = trim($_POST['appointment_date'] ?? date('Y-m-d'));
        $patientType = $_POST['patient_type'] ?? 'normal';
        $notes = trim($_POST['notes'] ?? '');

        if ($patientId <= 0) {
            $this->redirectWithError('reception/queue', 'Invalid Patient selection for manual appointment.');
        }

        if (strtotime($appointmentDate) < strtotime(date('Y-m-d'))) {
            $this->redirectWithError('reception/queue', 'Appointment date cannot be in the past.');
        }

        // Parse Vitals if provided
        $vitals = [];
        if (!empty($_POST['bp'])) $vitals[] = 'BP: ' . trim($_POST['bp']);
        if (!empty($_POST['weight'])) $vitals[] = 'Weight: ' . trim($_POST['weight']) . 'kg';
        if (!empty($_POST['pulse'])) $vitals[] = 'Pulse: ' . trim($_POST['pulse']) . 'bpm';
        
        $vitalsStr = implode(' | ', $vitals);
        if (!empty($vitalsStr)) {
            $notes = empty($notes) ? $vitalsStr : $vitalsStr . ' - ' . $notes;
        }

        $serialModel = new Serial();
        $db = $serialModel->getDb();
        $db->beginTransaction();

        try {
            // 1. Create Appointment
            $sqlApp = "INSERT INTO appointments (patient_id, chamber_id, appointment_date, appointment_type, status, notes, booked_by, created_at, updated_at) 
                       VALUES (:patient_id, :chamber_id, :date, :type, 'booked', :notes, :booked_by, NOW(), NOW())";
            $stmtApp = $db->prepare($sqlApp);
            $stmtApp->execute([
                'patient_id' => $patientId,
                'chamber_id' => $chamberId,
                'date' => $appointmentDate,
                'type' => $patientType,
                'notes' => $notes,
                'booked_by' => session('user_id')
            ]);
            $appointmentId = $db->lastInsertId();

            // 2. Reserve Serial Number & Queue Position for target date
            $maxSerial = $serialModel->getMaxSerialNumber($chamberId, $appointmentDate);
            $nextSerial = $maxSerial + 1;

            $maxPosition = $serialModel->getMaxQueuePosition($chamberId, $appointmentDate);
            $nextPosition = $maxPosition + 1;

            $tokenNumber = "TK-" . date('ymd', strtotime($appointmentDate)) . sprintf("%03d", $nextSerial);

            // Determine status: if target date is today, status is 'waiting'. If future date, status is 'waiting' reserved
            $serialModel->create([
                'appointment_id' => $appointmentId,
                'chamber_id' => $chamberId,
                'serial_date' => $appointmentDate,
                'serial_number' => $nextSerial,
                'queue_position' => $nextPosition,
                'patient_type' => $patientType,
                'status' => 'waiting',
                'token_number' => $tokenNumber,
                'notes' => 'Manual Reception Advance Booking: ' . $notes
            ]);

            $db->commit();

            // Trigger queue reorder for that date
            $engine = new QueueEngine();
            $engine->reorderQueue($chamberId, $appointmentDate);

            $formattedDate = date('d M Y', strtotime($appointmentDate));
            $this->redirectWithSuccess('reception/queue', "Advance Manual Appointment booked for {$formattedDate}! Token: {$tokenNumber} (Serial #{$nextSerial})");

        } catch (Exception $e) {
            $db->rollBack();
            $this->redirectWithError('reception/queue', 'Failed to book manual appointment: ' . $e->getMessage());
        }
    }

    /**
     * Check-in Advance Appointment to Live Queue
     */
    public function checkinAppointment(): void {
        $serialId = intval($_POST['serial_id'] ?? 0);
        if ($serialId <= 0) {
            $this->json(['error' => 'Invalid Serial ID'], 400);
        }

        $serialModel = new Serial();
        $serial = $serialModel->find($serialId);
        if (!$serial) {
            $this->json(['error' => 'Appointment serial not found'], 404);
        }

        $today = date('Y-m-d');
        $db = $serialModel->getDb();

        try {
            // Update appointment & serial to today's queue if required, set status to waiting
            $serialModel->update($serialId, [
                'serial_date' => $today,
                'status' => 'waiting',
                'called_at' => null
            ]);

            $engine = new QueueEngine();
            $engine->reorderQueue($serial['chamber_id'], $today);

            $this->json(['success' => true, 'message' => 'Patient checked in to today\'s live queue.']);
        } catch (Exception $e) {
            $this->json(['error' => $e->getMessage()], 500);
        }
    }
}
