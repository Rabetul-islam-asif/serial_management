<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\OtpCode;
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
        if (session('user_id')) {
            if (session('role') === 'patient') {
                $this->redirect('dashboard');
            } else {
                // Clear staff session so they can test/use patient booking
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION = [];
                session_destroy();
                session_start();
            }
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
     * Send OTP Code
     */
    public function sendOtp(): void {
        $phone = trim($_POST['phone'] ?? '');

        // Validation for BD phone numbers format or simple numeric length
        if (empty($phone) || !preg_match('/^[0-9]{11,15}$/', $phone)) {
            $this->redirectWithError('patient/login', 'Please enter a valid phone number.');
        }

        $otpModel = new OtpCode();
        $code = $otpModel->generate($phone);

        // Simulate SMS sending (log to file for local dev environment)
        $logDir = dirname(__DIR__, 2) . '/storage/logs';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        $logMessage = "[" . date('Y-m-d H:i:s') . "] Sent OTP: {$code} to Phone: {$phone}\n";
        file_put_contents($logDir . '/sms_sandbox.log', $logMessage, FILE_APPEND);

        // Store phone number in session for verification phase
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['otp_phone'] = $phone;

        $this->redirectWithSuccess('patient/otp/verify', 'OTP code sent successfully (logged to storage/logs/sms_sandbox.log).');
    }

    /**
     * Show OTP Code Verification Page
     */
    public function showVerifyOtp(): void {
        $phone = session('otp_phone');
        if (!$phone) {
            $this->redirect('patient/login');
        }
        $this->view('auth/verify-otp', ['phone' => $phone], 'auth');
    }

    /**
     * Verify OTP Code
     */
    public function verifyOtp(): void {
        $phone = session('otp_phone');
        $code = trim($_POST['code'] ?? '');

        if (!$phone) {
            $this->redirect('patient/login');
        }

        if (empty($code) || strlen($code) !== 6) {
            $this->redirectWithError('patient/otp/verify', 'Please enter a valid 6-digit code.');
        }

        $otpModel = new OtpCode();
        if ($otpModel->verify($phone, $code)) {
            // Find or create patient record for this number
            $patientModel = new Patient();
            $patient = $patientModel->findBy('phone', $phone);
            $patientName = 'Patient (' . substr($phone, -4) . ')';
            
            if (!$patient) {
                $patientModel->create([
                    'phone' => $phone,
                    'name' => $patientName,
                    'age' => 30,
                    'gender' => 'other'
                ]);
            } else {
                $patientName = $patient['name'];
            }

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            
            // Log in as patient
            $_SESSION['user_id'] = $phone; // use phone as identifier
            $_SESSION['name'] = $patientName;
            $_SESSION['role'] = 'patient';
            unset($_SESSION['otp_phone']);

            $redirect = $_SESSION['login_redirect'] ?? 'dashboard';
            unset($_SESSION['login_redirect']);

            if ($redirect === 'book') {
                $this->redirectWithSuccess('?redirect=book', 'Successfully logged in. You can now book your slot.');
            } else {
                $this->redirectWithSuccess('dashboard', 'Successfully logged in to Patient Portal.');
            }
        }

        $this->redirectWithError('patient/otp/verify', 'Invalid or expired OTP code.');
    }
}
