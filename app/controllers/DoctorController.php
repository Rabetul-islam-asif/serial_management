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
     * Update Doctor Profile details
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

        if (empty($name) || empty($degree) || empty($specialization)) {
            $this->redirectWithError('doctor/profile/edit', 'Please fill in all required fields.');
        }

        $doctorModel->update($profile['id'], [
            'name' => $name,
            'degree' => $degree,
            'specialization' => $specialization,
            'bmdc_number' => $bmdc,
            'consultation_fee' => $fee,
            'bio' => $bio
        ]);

        $this->redirectWithSuccess('doctor/profile/edit', 'Profile details updated successfully.');
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
