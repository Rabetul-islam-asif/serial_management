<?php

namespace App\Controllers;

use App\Models\DoctorProfile;
use App\Models\Chamber;

class DoctorController extends BaseController {
    
    /**
     * Edit Doctor Profile (Admin side)
     */
    public function editProfile(): void {
        $doctorModel = new DoctorProfile();
        $profile = $doctorModel->findBy('user_id', session('user_id'));

        if (!$profile) {
            $this->redirectWithError('dashboard', 'Profile not found.');
        }

        $this->view('doctor/edit-profile', [
            'title' => 'Edit My Profile',
            'profile' => $profile
        ]);
    }

    /**
     * Update Doctor Profile details (handles all landing page fields)
     */
    public function updateProfile(): void {
        $doctorModel = new DoctorProfile();
        $profile = $doctorModel->findBy('user_id', session('user_id'));

        if (!$profile) {
            $this->redirectWithError('dashboard', 'Profile not found.');
        }

        $name = trim($_POST['name'] ?? '');
        $degree = trim($_POST['degree'] ?? '');
        $specialization = trim($_POST['specialization'] ?? '');
        $bmdc = trim($_POST['bmdc'] ?? '');
        $fee = floatval($_POST['fee'] ?? 0);
        $bio = trim($_POST['bio'] ?? '');
        $hospital = trim($_POST['hospital'] ?? '');
        $experienceYears = intval($_POST['experience_years'] ?? 0);
        $languages = trim($_POST['languages'] ?? '');

        if (empty($name) || empty($degree) || empty($specialization)) {
            $this->redirectWithError('doctor/profile/edit', 'Please fill in all required fields.');
        }

        // Convert comma-separated languages into JSON array
        $languagesJson = null;
        if (!empty($languages)) {
            $langArray = array_map('trim', explode(',', $languages));
            $langArray = array_filter($langArray);
            $languagesJson = json_encode(array_values($langArray));
        }

        $updateData = [
            'name' => $name,
            'degree' => $degree,
            'specialization' => $specialization,
            'bmdc_number' => $bmdc,
            'consultation_fee' => $fee,
            'bio' => $bio,
            'hospital' => $hospital,
            'experience_years' => $experienceYears,
            'languages' => $languagesJson
        ];

        // Handle photo upload
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['photo']['tmp_name'];
            $fileName = $_FILES['photo']['name'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
            if (in_array($fileExtension, $allowedExtensions)) {
                $uploadDir = dirname(__DIR__, 2) . '/public/uploads/photos/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                $newFileName = 'doctor_' . uniqid() . '.' . $fileExtension;
                $destPath = $uploadDir . $newFileName;
                
                if (move_uploaded_file($fileTmpPath, $destPath)) {
                    $updateData['photo'] = 'uploads/photos/' . $newFileName;
                }
            }
        }

        $doctorModel->update($profile['id'], $updateData);

        $this->redirectWithSuccess('doctor/profile/edit', 'Profile and landing page info updated successfully.');
    }

    /**
     * Manage Chambers & Schedules
     */
    public function manageChambers(): void {
        $chamberModel = new Chamber();
        $doctorModel = new DoctorProfile();
        $profile = $doctorModel->findBy('user_id', session('user_id'));
        
        $chambers = $chamberModel->getActiveChambers($profile['id']);

        // Fetch schedules for each chamber
        foreach ($chambers as &$chamber) {
            $chamber['schedules'] = $chamberModel->getSchedules($chamber['id']);
        }

        $this->view('doctor/chambers', [
            'title' => 'Chamber Management',
            'chambers' => $chambers
        ]);
    }

    /**
     * Add New Chamber (Doctor ID = 1)
     */
    public function addChamber(): void {
        $name = trim($_POST['name'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $mapUrl = trim($_POST['google_map_url'] ?? '');

        if (empty($name) || empty($address)) {
            $this->redirectWithError('doctor/chambers', 'Chamber Name and Address are required.');
        }

        $doctorModel = new DoctorProfile();
        $profile = $doctorModel->findBy('user_id', session('user_id'));
        $doctorId = $profile['id'] ?? 1;

        $chamberModel = new Chamber();
        $chamberId = $chamberModel->create([
            'doctor_id' => $doctorId,
            'name' => $name,
            'address' => $address,
            'phone' => $phone,
            'google_map_url' => $mapUrl,
            'is_active' => 1,
            'sort_order' => 1
        ]);

        // Default initial schedule for 6 days
        $db = $chamberModel->getDb();
        for ($day = 1; $day <= 7; $day++) {
            if ($day === 6) continue; // Skip Friday by default
            $sql = "INSERT INTO chamber_schedules (chamber_id, day_of_week, start_time, end_time, max_patients, is_active, created_at, updated_at) 
                    VALUES (:chamber_id, :day, '17:00:00', '21:00:00', 30, 1, NOW(), NOW())";
            $stmt = $db->prepare($sql);
            $stmt->execute(['chamber_id' => $chamberId, 'day' => $day]);
        }

        $this->redirectWithSuccess('doctor/chambers', 'New Chamber location created successfully.');
    }

    /**
     * Update Existing Chamber Details
     */
    public function updateChamber(): void {
        $chamberId = intval($_POST['chamber_id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $mapUrl = trim($_POST['google_map_url'] ?? '');

        if ($chamberId <= 0 || empty($name) || empty($address)) {
            $this->redirectWithError('doctor/chambers', 'Invalid Chamber selection or missing required fields.');
        }

        $chamberModel = new Chamber();
        $chamberModel->update($chamberId, [
            'name' => $name,
            'address' => $address,
            'phone' => $phone,
            'google_map_url' => $mapUrl
        ]);

        $this->redirectWithSuccess('doctor/chambers', 'Chamber details updated successfully.');
    }

    /**
     * Update Chamber Visiting Schedules
     */
    public function updateChamberSchedule(): void {
        $chamberId = intval($_POST['chamber_id'] ?? 0);
        $schedules = $_POST['schedules'] ?? [];

        if ($chamberId <= 0 || empty($schedules)) {
            $this->redirectWithError('doctor/chambers', 'Invalid schedule data.');
        }

        $chamberModel = new Chamber();
        $db = $chamberModel->getDb();

        try {
            foreach ($schedules as $day => $s) {
                $dayNum = intval($day);
                $isActive = isset($s['is_active']) ? 1 : 0;
                $startTime = !empty($s['start_time']) ? $s['start_time'] : '17:00:00';
                $endTime = !empty($s['end_time']) ? $s['end_time'] : '21:00:00';
                $maxPatients = intval($s['max_patients'] ?? 30);

                $sql = "INSERT INTO chamber_schedules (chamber_id, day_of_week, start_time, end_time, max_patients, is_active, created_at, updated_at)
                        VALUES (:chamber_id, :day, :start_time, :end_time, :max_patients, :is_active, NOW(), NOW())
                        ON DUPLICATE KEY UPDATE 
                            start_time = VALUES(start_time),
                            end_time = VALUES(end_time),
                            max_patients = VALUES(max_patients),
                            is_active = VALUES(is_active),
                            updated_at = NOW()";
                $stmt = $db->prepare($sql);
                $stmt->execute([
                    'chamber_id' => $chamberId,
                    'day' => $dayNum,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'max_patients' => $maxPatients,
                    'is_active' => $isActive
                ]);
            }

            $this->redirectWithSuccess('doctor/chambers', 'Chamber visiting schedules updated successfully.');
        } catch (Exception $e) {
            $this->redirectWithError('doctor/chambers', 'Failed to update schedule: ' . $e->getMessage());
        }
    }
}
