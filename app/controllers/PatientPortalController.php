<?php

namespace App\Controllers;

use App\Models\Patient;
use App\Models\Prescription;

class PatientPortalController extends BaseController {

    /**
     * Show Patient Cloud Dashboard Timeline
     */
    public function index(): void {
        // Authenticated check
        if (session('role') !== 'patient') {
            $this->redirect('logout');
        }

        $phone = session('user_id'); // Phone number is stored as patient user_id
        
        $patientModel = new Patient();
        $patient = $patientModel->findBy('phone', $phone);

        if (!$patient) {
            // Log out if patient details don't exist
            $this->redirect('logout');
        }

        // Fetch all prescriptions written for this patient
        $sql = "SELECT pr.*, v.chief_complaint, v.diagnosis, v.next_visit_date, 
                       dp.name as doctor_name, dp.specialization as doctor_spec,
                       c.name as chamber_name
                FROM prescriptions pr
                INNER JOIN visits v ON pr.visit_id = v.id
                INNER JOIN chambers c ON v.chamber_id = c.id
                INNER JOIN doctor_profile dp ON c.doctor_id = dp.id
                WHERE pr.patient_id = :patient_id 
                ORDER BY pr.rx_date DESC, pr.id DESC";
        
        $prescriptions = $patientModel->getDb()->prepare($sql);
        $prescriptions->execute(['patient_id' => $patient['id']]);
        $visitTimeline = $prescriptions->fetchAll();

        // Fetch invoices
        $sqlInv = "SELECT * FROM invoices WHERE patient_id = :patient_id ORDER BY id DESC";
        $invoicesQuery = $patientModel->getDb()->prepare($sqlInv);
        $invoicesQuery->execute(['patient_id' => $patient['id']]);
        $invoices = $invoicesQuery->fetchAll();

        $this->view('patient/dashboard', [
            'title' => 'My Prescription Cloud',
            'patient' => $patient,
            'timeline' => $visitTimeline,
            'invoices' => $invoices
        ], 'public'); // patient uses clean public layout
    }
}
