<?php

namespace App\Controllers;

class DashboardController extends BaseController {
    
    /**
     * Show main dashboard based on user role
     */
    public function index(): void {
        $role = session('role');
        
        switch ($role) {
            case 'admin':
                $this->view('dashboard/admin', [
                    'title' => 'Doctor Dashboard',
                    'username' => session('name')
                ]);
                break;
                
            case 'receptionist':
                $this->redirect('reception/queue');
                break;
                
            case 'patient':
                $patientPortal = new \App\Controllers\PatientPortalController();
                $patientPortal->index();
                break;
                
            default:
                $this->redirect('logout');
        }
    }
}
