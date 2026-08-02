<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Patient;

class AuthController extends BaseController {
    
    /**
     * Show Doctor/Receptionist Login Form
     */
    public function showLogin(): void {
        if (session('user_id')) {
            if (session('role') === 'patient') {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION = [];
                session_destroy();
                session_start();
            } else {
                $this->redirect('dashboard');
            }
        }
        $this->view('auth/login', [], 'auth');
    }

    /**
     * Handle Doctor/Receptionist Login
     */
    public function login(): void {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $this->redirectWithError('admin', 'Please fill in all required fields.');
        }

        $userModel = new User();
        $user = $userModel->authenticate($email, $password);

        if ($user) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['avatar'] = $user['avatar'];

            // Log action in Audit Logs (to be configured in audit logs system)
            $this->redirectWithSuccess('dashboard', 'Welcome back, ' . $user['name'] . '!');
        }

        $this->redirectWithError('admin', 'Invalid email or password.');
    }

    /**
     * Handle Logout
     */
    public function logout(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = [];
        session_destroy();
        
        // Restart clean session for CSRF token
        session_start();
        $this->redirect('admin');
    }

    /**
     * Show Patient Phone Entry Form
     */
    public function showPatientLogin(): void {
        if (session('user_id') && session('role') === 'patient') {
            $this->redirect('patient/dashboard');
        }
        if (isset($_GET['redirect'])) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['login_redirect'] = $_GET['redirect'];
        }
        $this->view('auth/otp', [], 'auth');
    }

    /**
     * Handle Patient Phone + Password Login
     */
    public function patientLogin(): void {
        $phone = trim($_POST['phone'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($phone) || empty($password)) {
            $this->redirectWithError('patient/login', 'Please fill in all fields.');
        }

        if (!preg_match('/^[0-9]{11,15}$/', $phone)) {
            $this->redirectWithError('patient/login', 'Please enter a valid phone number.');
        }

        $accountModel = new \App\Models\PhoneAccount();
        $account = $accountModel->authenticate($phone, $password);

        if ($account) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['user_id'] = $account['id'];
            $_SESSION['phone'] = $phone;
            $_SESSION['name'] = 'Patient (' . substr($phone, -4) . ')';
            $_SESSION['role'] = 'patient';

            $redirect = $_SESSION['login_redirect'] ?? 'patient/dashboard';
            unset($_SESSION['login_redirect']);

            $this->redirectWithSuccess($redirect, 'Welcome to Patient Portal!');
        }

        $this->redirectWithError('patient/login', 'Invalid phone number or password.');
    }

    /**
     * Show Forgot Password Form
     */
    public function showForgotPassword(): void {
        $this->view('auth/forgot-password', [], 'auth');
    }

    /**
     * Reset Password and SMS new one
     */
    public function resetPassword(): void {
        $phone = trim($_POST['phone'] ?? '');
        if (empty($phone) || !preg_match('/^[0-9]{11,15}$/', $phone)) {
            $this->redirectWithError('patient/forgot-password', 'Please enter a valid phone number.');
        }

        $accountModel = new \App\Models\PhoneAccount();
        $account = $accountModel->findByPhone($phone);

        if (!$account) {
            $this->redirectWithError('patient/forgot-password', 'No account found with this phone number. Please book an appointment first.');
        }

        $newPassword = $accountModel->resetPassword($account['id']);

        // Simulate SMS
        $logDir = dirname(__DIR__, 2) . '/storage/logs';
        if (!is_dir($logDir)) mkdir($logDir, 0755, true);
        $logMessage = "[" . date('Y-m-d H:i:s') . "] Password Reset — Phone: {$phone}, New Password: {$newPassword}\n";
        file_put_contents($logDir . '/sms_sandbox.log', $logMessage, FILE_APPEND);

        $this->redirectWithSuccess('patient/login', 'New password sent to your phone number. (Check storage/logs/sms_sandbox.log)');
    }
}
