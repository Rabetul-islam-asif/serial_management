<?php

/**
 * Route Definitions for Doctor Serial Cloud
 */

// Instantiate routing helper inside router context
// Note: $router is instantiated in public/index.php

// -------------------------------------------------------------
// Guest Routes (Authentication & Public Pages)
// -------------------------------------------------------------
$router->get('/', [\App\Controllers\PublicController::class, 'showProfile'], [], 'home');
$router->get('/profile', [\App\Controllers\PublicController::class, 'showProfile'], [], 'doctor.profile');
$router->get('/admin', [\App\Controllers\AuthController::class, 'showLogin'], [], 'login');
$router->post('/admin', [\App\Controllers\AuthController::class, 'login'], ['csrf', 'rate_limit:login']);
$router->get('/logout', [\App\Controllers\AuthController::class, 'logout'], [], 'logout');

// Patient OTP Guest routes
$router->get('/patient/login', [\App\Controllers\AuthController::class, 'showPatientLogin'], [], 'patient.login');
$router->post('/patient/otp/send', [\App\Controllers\AuthController::class, 'sendOtp'], ['csrf', 'rate_limit:otp']);
$router->get('/patient/otp/verify', [\App\Controllers\AuthController::class, 'showVerifyOtp'], [], 'patient.otp.verify');
$router->post('/patient/otp/verify', [\App\Controllers\AuthController::class, 'verifyOtp'], ['csrf']);
$router->post('/appointment/book', [\App\Controllers\SerialController::class, 'bookOnlineAppointment'], ['csrf']);

// -------------------------------------------------------------
// Doctor & Receptionist Shared Authenticated Routes
// -------------------------------------------------------------
$router->get('/dashboard', [\App\Controllers\DashboardController::class, 'index'], ['auth'], 'dashboard');

// Doctor Profile & Chamber Management routes
$router->get('/doctor/profile/edit', [\App\Controllers\DoctorController::class, 'editProfile'], ['auth', 'role:admin'], 'doctor.profile.edit');
$router->post('/doctor/profile/edit', [\App\Controllers\DoctorController::class, 'updateProfile'], ['auth', 'role:admin', 'csrf']);
$router->get('/doctor/chambers', [\App\Controllers\DoctorController::class, 'manageChambers'], ['auth', 'role:admin'], 'doctor.chambers');

// Patient Search autocomplete route
$router->get('/patient/search', [\App\Controllers\PatientController::class, 'search'], ['auth']);

// Live Queue Management (Reception & Actions) routes
$router->get('/reception/queue', [\App\Controllers\QueueBoardController::class, 'receptionPanel'], ['auth', 'role:receptionist'], 'reception.queue');
$router->post('/reception/queue/settings', [\App\Controllers\QueueBoardController::class, 'updateSettings'], ['auth', 'role:receptionist', 'csrf']);
$router->post('/reception/queue/add', [\App\Controllers\SerialController::class, 'createSerial'], ['auth', 'role:receptionist', 'csrf']);
$router->post('/reception/queue/call', [\App\Controllers\SerialController::class, 'callPatient'], ['auth', 'role:receptionist']);
$router->post('/reception/queue/complete', [\App\Controllers\SerialController::class, 'completePatient'], ['auth', 'role:receptionist']);
$router->post('/reception/queue/miss', [\App\Controllers\SerialController::class, 'missPatient'], ['auth', 'role:receptionist']);
$router->post('/reception/queue/hold', [\App\Controllers\SerialController::class, 'holdPatient'], ['auth', 'role:receptionist']);
$router->post('/reception/queue/rejoin', [\App\Controllers\SerialController::class, 'rejoinPatient'], ['auth', 'role:receptionist']);
$router->post('/reception/prescription/upload', [\App\Controllers\SerialController::class, 'uploadPrescription'], ['auth', 'role:receptionist', 'csrf']);

// Public live board feeds
$router->get('/queue/board', [\App\Controllers\QueueBoardController::class, 'show'], [], 'queue.board');
$router->get('/queue/board/tv', [\App\Controllers\QueueBoardController::class, 'tvMode'], [], 'queue.board.tv');
$router->get('api/queue/status', [\App\Controllers\QueueBoardController::class, 'apiStatus'], []);

// Prescription Editor routes
$router->get('/doctor/medicine/search', [\App\Controllers\PrescriptionController::class, 'searchMedicine'], ['auth']);
$router->post('/doctor/medicine/favorite', [\App\Controllers\PrescriptionController::class, 'addFavorite'], ['auth']);
$router->get('/doctor/prescription/new', [\App\Controllers\PrescriptionController::class, 'create'], ['auth', 'role:admin'], 'doctor.prescription.new');
$router->post('/doctor/prescription/new', [\App\Controllers\PrescriptionController::class, 'store'], ['auth', 'role:admin', 'csrf']);
$router->get('/doctor/prescription/print', [\App\Controllers\PrescriptionController::class, 'printView'], ['auth']);

// Patient Portal dashboard route
$router->get('/patient/dashboard', [\App\Controllers\PatientPortalController::class, 'index'], ['auth', 'role:patient'], 'patient.dashboard');

// Admin panel management routes (Phase 5)
$router->get('/admin/receptionists', [\App\Controllers\AdminController::class, 'receptionists'], ['auth', 'role:admin'], 'admin.receptionists');
$router->post('/admin/receptionists/new', [\App\Controllers\AdminController::class, 'createReceptionist'], ['auth', 'role:admin', 'csrf']);
$router->get('/admin/patients', [\App\Controllers\AdminController::class, 'patients'], ['auth', 'role:admin'], 'admin.patients');
$router->get('/admin/audit-logs', [\App\Controllers\AdminController::class, 'auditLogs'], ['auth', 'role:admin'], 'admin.audit-logs');
$router->get('/admin/analytics', [\App\Controllers\AnalyticsController::class, 'show'], ['auth', 'role:admin'], 'admin.analytics');
