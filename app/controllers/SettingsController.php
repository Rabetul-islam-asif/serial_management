<?php

namespace App\Controllers;

use App\Models\Serial;
use App\Models\Patient;
use Exception;

class SettingsController extends BaseController {

    /**
     * Settings & Reports Overview Page
     */
    public function index(): void {
        $chamberId = intval($_GET['chamber_id'] ?? 1);
        $serialModel = new Serial();
        $db = $serialModel->getDb();

        // 1. Date-wise Attendance & Visit Breakdown Reports (Past 30 days & upcoming)
        $dateReportSql = "
            SELECT 
                s.serial_date,
                COUNT(s.id) as total_patients,
                SUM(CASE WHEN a.appointment_type = 'appointment' OR s.notes LIKE '%Online%' THEN 1 ELSE 0 END) as online_count,
                SUM(CASE WHEN a.appointment_type != 'appointment' AND s.notes NOT LIKE '%Online%' THEN 1 ELSE 0 END) as walkin_count,
                SUM(CASE WHEN s.status = 'completed' THEN 1 ELSE 0 END) as completed_count,
                SUM(CASE WHEN s.status = 'missed' THEN 1 ELSE 0 END) as missed_count,
                SUM(CASE WHEN s.status = 'hold' THEN 1 ELSE 0 END) as hold_count
            FROM serials s
            LEFT JOIN appointments a ON s.appointment_id = a.id
            WHERE s.chamber_id = :chamber_id
            GROUP BY s.serial_date
            ORDER BY s.serial_date DESC
            LIMIT 30
        ";
        $stmtReport = $db->prepare($dateReportSql);
        $stmtReport->execute(['chamber_id' => $chamberId]);
        $dateReports = $stmtReport->fetchAll();

        // 2. Fetch Quota & Chamber Settings
        $settingsStmt = $db->prepare("SELECT setting_key, setting_value FROM queue_settings WHERE chamber_id = :chamber_id");
        $settingsStmt->execute(['chamber_id' => $chamberId]);
        $rawSettings = $settingsStmt->fetchAll();

        $settings = [
            'max_online_appointments' => 20,
            'max_offline_appointments' => 30,
            'avg_consultation_time' => 7
        ];

        foreach ($rawSettings as $rs) {
            $key = $rs['setting_key'];
            $val = json_decode($rs['setting_value'], true);
            $settings[$key] = is_numeric($rs['setting_value']) ? intval($rs['setting_value']) : ($val ?? $rs['setting_value']);
        }

        // 3. Fetch Recent Patient Cards for quick editing
        $patientModel = new Patient();
        $recentPatients = $patientModel->query("SELECT * FROM patients WHERE deleted_at IS NULL ORDER BY id DESC LIMIT 20");

        $this->view('settings/index', [
            'title' => 'Settings & Analytics Reports',
            'date_reports' => $dateReports,
            'settings' => $settings,
            'recent_patients' => $recentPatients,
            'chamber_id' => $chamberId
        ], 'app');
    }

    /**
     * Save Chamber Quota & Capacity Settings
     */
    public function updateQuotas(): void {
        $chamberId = intval($_POST['chamber_id'] ?? 1);
        $maxOnline = intval($_POST['max_online_appointments'] ?? 20);
        $maxOffline = intval($_POST['max_offline_appointments'] ?? 30);
        $avgTime = intval($_POST['avg_consultation_time'] ?? 7);

        $serialModel = new Serial();
        $db = $serialModel->getDb();

        $items = [
            'max_online_appointments' => $maxOnline,
            'max_offline_appointments' => $maxOffline,
            'avg_consultation_time' => $avgTime
        ];

        try {
            foreach ($items as $key => $val) {
                $sql = "INSERT INTO queue_settings (chamber_id, setting_key, setting_value, description, created_at, updated_at) 
                        VALUES (:chamber_id, :key, :val, :desc, NOW(), NOW())
                        ON DUPLICATE KEY UPDATE setting_value = :val2, updated_at = NOW()";
                $stmt = $db->prepare($sql);
                $stmt->execute([
                    'chamber_id' => $chamberId,
                    'key' => $key,
                    'val' => json_encode($val),
                    'desc' => ucfirst(str_replace('_', ' ', $key)),
                    'val2' => json_encode($val)
                ]);
            }

            $this->redirectWithSuccess('settings', 'Chamber slot quotas and wait time settings updated successfully.');
        } catch (Exception $e) {
            $this->redirectWithError('settings', 'Failed to update settings: ' . $e->getMessage());
        }
    }

    /**
     * Customize Patient Card Details
     */
    public function updatePatient(): void {
        $patientId = intval($_POST['patient_id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $age = intval($_POST['age'] ?? 0);
        $gender = $_POST['gender'] ?? 'male';
        $blood = trim($_POST['blood_group'] ?? '');
        $address = trim($_POST['address'] ?? '');

        if ($patientId <= 0 || empty($name) || empty($phone)) {
            if ($this->isAjax()) {
                $this->json(['error' => 'Patient ID, Name, and Mobile phone are required.'], 400);
            }
            $this->redirectWithError('settings', 'Required fields missing.');
        }

        $patientModel = new Patient();
        try {
            $patientModel->update($patientId, [
                'name' => $name,
                'phone' => $phone,
                'age' => $age,
                'gender' => $gender,
                'blood_group' => $blood,
                'address' => $address
            ]);

            if ($this->isAjax()) {
                $this->json(['success' => true, 'message' => 'Patient profile updated successfully.']);
            }
            $this->redirectWithSuccess('settings', 'Patient details updated successfully.');
        } catch (Exception $e) {
            if ($this->isAjax()) {
                $this->json(['error' => $e->getMessage()], 500);
            }
            $this->redirectWithError('settings', 'Failed to update patient: ' . $e->getMessage());
        }
    }
}
