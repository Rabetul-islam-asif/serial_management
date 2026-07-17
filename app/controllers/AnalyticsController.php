<?php

namespace App\Controllers;

use App\Models\User;

class AnalyticsController extends BaseController {

    /**
     * Render Analytics Dashboard View
     */
    public function show(): void {
        $db = (new User())->getDb();

        // 1. Fetch total counts
        $totalPatients = $db->query("SELECT COUNT(*) as cnt FROM patients WHERE deleted_at IS NULL")->fetch()['cnt'];
        $totalVisits = $db->query("SELECT COUNT(*) as cnt FROM visits")->fetch()['cnt'];
        $totalRevenue = $db->query("SELECT SUM(total) as rev FROM invoices WHERE payment_status = 'paid'")->fetch()['rev'] ?? 0;

        // 2. Chamber performance (Completed vs Cancelled)
        $completedSerials = $db->query("SELECT COUNT(*) as cnt FROM serials WHERE status = 'completed'")->fetch()['cnt'];
        $missedSerials = $db->query("SELECT COUNT(*) as cnt FROM serials WHERE status = 'missed'")->fetch()['cnt'];
        
        $this->view('admin/analytics', [
            'title' => 'Analytics & Insights',
            'total_patients' => $totalPatients,
            'total_visits' => $totalVisits,
            'total_revenue' => $totalRevenue,
            'completed_serials' => $completedSerials,
            'missed_serials' => $missedSerials
        ]);
    }
}
