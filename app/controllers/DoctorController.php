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
}
