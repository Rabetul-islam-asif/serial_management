<?php

namespace App\Controllers;

use App\Models\DoctorProfile;
use App\Models\Chamber;

class PublicController extends BaseController {
    
    /**
     * Show Doctor Public Profile Page
     */
    public function showProfile(): void {
        $doctorModel = new DoctorProfile();
        // Single doctor app: default doctor ID is 1
        $profile = $doctorModel->find(1);
        
        if (!$profile) {
            http_response_code(404);
            echo "Doctor profile not initialized. Please run seeds.";
            exit;
        }

        $chamberModel = new Chamber();
        $chambers = $chamberModel->getActiveChambers($profile['id']);
        
        // Fetch schedules for each chamber
        foreach ($chambers as &$chamber) {
            $chamber['schedules'] = $chamberModel->getSchedules($chamber['id']);
        }

        // Fetch gallery, services, awards, education (Phases 2-5)
        // For Phase 2 we will query database or pass mock templates if tables empty
        $education = $doctorModel->getDb()->query("SELECT * FROM doctor_education WHERE doctor_id = {$profile['id']} ORDER BY sort_order ASC")->fetchAll();
        $awards = $doctorModel->getDb()->query("SELECT * FROM doctor_awards WHERE doctor_id = {$profile['id']} ORDER BY sort_order ASC")->fetchAll();
        $services = $doctorModel->getDb()->query("SELECT * FROM doctor_services WHERE doctor_id = {$profile['id']} ORDER BY sort_order ASC")->fetchAll();
        $gallery = $doctorModel->getDb()->query("SELECT * FROM doctor_gallery WHERE doctor_id = {$profile['id']} ORDER BY sort_order ASC")->fetchAll();

        $this->view('public/profile', [
            'title' => $profile['name'] . ' — ' . $profile['specialization'],
            'profile' => $profile,
            'chambers' => $chambers,
            'education' => $education,
            'awards' => $awards,
            'services' => $services,
            'gallery' => $gallery
        ], 'public');
    }
}
