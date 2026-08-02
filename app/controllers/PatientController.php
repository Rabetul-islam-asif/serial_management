<?php

namespace App\Controllers;

use App\Models\Patient;

class PatientController extends BaseController {
    
    /**
     * Search patients via AJAX autocomplete
     */
    public function search(): void {
        $query = trim($_GET['q'] ?? '');
        
        if (strlen($query) < 2) {
            $this->json([]);
        }

        $patientModel = new Patient();
        $results = $patientModel->search($query);
        
        $this->json($results);
    }

    /**
     * Register a new patient
     */
    public function register(): void {
        $phone = trim($_POST['phone'] ?? '');
        $name = trim($_POST['name'] ?? '');
        $age = intval($_POST['age'] ?? 0);
        $gender = $_POST['gender'] ?? 'male';
        $blood = trim($_POST['blood_group'] ?? '');
        $address = trim($_POST['address'] ?? '');

        if (empty($phone) || empty($name) || $age <= 0) {
            $this->json(['error' => 'Please fill in all required fields.'], 400);
        }

        $patientModel = new Patient();

        $phoneAccountModel = new \App\Models\PhoneAccount();
        $account = $phoneAccountModel->findByPhone($phone);

        if (!$account) {
            $plainPassword = $phoneAccountModel->generatePassword();
            $accountId = $phoneAccountModel->createAccount($phone, $plainPassword);
        } else {
            $accountId = $account['id'];
        }

        $patientUid = $patientModel->generatePatientUid($name);

        $patientId = $patientModel->create([
            'phone' => $phone,
            'name' => $name,
            'age' => $age,
            'gender' => $gender,
            'blood_group' => $blood,
            'address' => $address,
            'phone_account_id' => $accountId,
            'patient_uid' => $patientUid
        ]);

        $newPatient = $patientModel->find($patientId);
        $this->json($newPatient);
    }
}
