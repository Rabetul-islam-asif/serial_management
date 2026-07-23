<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Patient;

class AdminController extends BaseController {

    /**
     * List and manage Receptionists (Admin only)
     */
    public function receptionists(): void {
        $userModel = new User();
        // Fetch all receptionist users
        $sql = "SELECT * FROM users WHERE role = 'receptionist' AND deleted_at IS NULL ORDER BY name ASC";
        $receptionists = $userModel->getDb()->query($sql)->fetchAll();

        $this->view('admin/receptionists', [
            'title' => 'Manage Receptionists',
            'receptionists' => $receptionists
        ]);
    }

    /**
     * Create receptionist account
     */
    public function createReceptionist(): void {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $password = $_POST['password'] ?? 'password';

        if (empty($name) || empty($email) || empty($phone)) {
            $this->redirectWithError('admin/receptionists', 'Please fill in all required fields.');
        }

        $userModel = new User();
        
        // Check if email already registered
        $existing = $userModel->findBy('email', $email);
        if ($existing) {
            $this->redirectWithError('admin/receptionists', 'Email address is already registered.');
        }

        $userModel->create([
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'password_hash' => password_hash($password, PASSWORD_BCRYPT),
            'role' => 'receptionist',
            'is_active' => 1
        ]);

        $this->redirectWithSuccess('admin/receptionists', 'Receptionist account created successfully.');
    }

    /**
     * Patient Directory lookup
     */
    public function patients(): void {
        $patientModel = new Patient();
        $patients = $patientModel->all('name ASC');

        $this->view('admin/patients', [
            'title' => 'Patient Directory',
            'patients' => $patients
        ]);
    }

    /**
     * View System Audit Logs
     */
    public function auditLogs(): void {
        $db = (new User())->getDb();
        $sql = "SELECT al.*, u.name as user_name 
                FROM audit_logs al
                LEFT JOIN users u ON al.user_id = u.id 
                ORDER BY al.id DESC 
                LIMIT 50";
        $logs = $db->query($sql)->fetchAll();

        $this->view('admin/audit-logs', [
            'title' => 'System Audit Logs',
            'logs' => $logs
        ]);
    }

    /**
     * Reset Receptionist Password
     */
    public function resetReceptionistPassword(): void {
        $userId = intval($_POST['user_id'] ?? 0);
        $newPassword = $_POST['new_password'] ?? '';

        if ($userId <= 0 || empty($newPassword)) {
            $this->redirectWithError('admin/receptionists', 'User ID and new password are required.');
        }

        $userModel = new User();
        $userModel->update($userId, [
            'password_hash' => password_hash($newPassword, PASSWORD_BCRYPT)
        ]);

        $this->redirectWithSuccess('admin/receptionists', 'Receptionist password updated successfully.');
    }
}
