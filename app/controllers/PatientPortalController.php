<?php

namespace App\Controllers;

use App\Models\Patient;
use App\Models\Prescription;

class PatientPortalController extends BaseController {

    /**
     * Show Member Selector (list all patients under this phone account)
     */
    public function index(): void {
        if (session('role') !== 'patient') {
            $this->redirect('logout');
        }

        $accountId = session('user_id');
        $patientModel = new Patient();
        $members = $patientModel->findByPhoneAccount($accountId);

        if (empty($members)) {
            $this->view('patient/members', [
                'title' => 'My Family Members',
                'members' => [],
                'phone' => session('phone')
            ], 'public');
            return;
        }

        if (count($members) === 1) {
            // Auto-redirect to the single member's detail
            $this->redirect('patient/member?uid=' . urlencode($members[0]['patient_uid']));
            return;
        }

        $this->view('patient/members', [
            'title' => 'My Family Members',
            'members' => $members,
            'phone' => session('phone')
        ], 'public');
    }

    /**
     * Show individual member detail with prescriptions and health history
     */
    public function memberDetail(): void {
        if (session('role') !== 'patient') {
            $this->redirect('logout');
        }

        $uid = trim($_GET['uid'] ?? '');
        $accountId = session('user_id');

        if (empty($uid)) {
            $this->redirect('patient/dashboard');
        }

        $patientModel = new Patient();
        $patient = $patientModel->findByUid($uid);

        if (!$patient || $patient['phone_account_id'] != $accountId) {
            $this->redirectWithError('patient/dashboard', 'Access denied or member not found.');
        }

        // Fetch prescriptions
        $sql = "SELECT pr.*, v.chief_complaint, v.diagnosis, v.next_visit_date, 
                       dp.name as doctor_name, dp.specialization as doctor_spec,
                       c.name as chamber_name
                FROM prescriptions pr
                INNER JOIN visits v ON pr.visit_id = v.id
                INNER JOIN chambers c ON v.chamber_id = c.id
                INNER JOIN doctor_profile dp ON c.doctor_id = dp.id
                WHERE pr.patient_id = :patient_id 
                ORDER BY pr.rx_date DESC, pr.id DESC";
        
        $stmt = $patientModel->getDb()->prepare($sql);
        $stmt->execute(['patient_id' => $patient['id']]);
        $visitTimeline = $stmt->fetchAll();

        // Fetch invoices
        $sqlInv = "SELECT * FROM invoices WHERE patient_id = :patient_id ORDER BY id DESC";
        $invStmt = $patientModel->getDb()->prepare($sqlInv);
        $invStmt->execute(['patient_id' => $patient['id']]);
        $invoices = $invStmt->fetchAll();

        // Count total members for back-link logic
        $allMembers = $patientModel->findByPhoneAccount($accountId);
        $showBackLink = count($allMembers) > 1;

        $this->view('patient/dashboard', [
            'title' => 'My Prescription Cloud',
            'patient' => $patient,
            'timeline' => $visitTimeline,
            'invoices' => $invoices,
            'showBackLink' => $showBackLink
        ], 'public');
    }
}
