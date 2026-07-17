<?php

namespace App\Controllers;

use App\Models\Serial;
use App\Models\QueueEngine;

class QueueBoardController extends BaseController {

    /**
     * Public Live Queue Board View
     */
    public function show(): void {
        $chamberId = intval($_GET['chamber_id'] ?? 1);
        $date = date('Y-m-d');

        $serialModel = new Serial();
        $queue = $serialModel->getQueue($chamberId, $date);
        
        $serving = $serialModel->getCurrentServing($chamberId, $date);
        $next = $serialModel->getNextWaiting($chamberId, $date);

        $this->view('public/queue-board', [
            'title' => 'Live Queue Board',
            'queue' => $queue,
            'serving' => $serving,
            'next' => $next,
            'chamber_id' => $chamberId
        ], 'public');
    }

    /**
     * TV Mode view (high-contrast, minimal layout)
     */
    public function tvMode(): void {
        $chamberId = intval($_GET['chamber_id'] ?? 1);
        $date = date('Y-m-d');

        $serialModel = new Serial();
        $queue = $serialModel->getQueue($chamberId, $date);
        
        $serving = $serialModel->getCurrentServing($chamberId, $date);
        $next = $serialModel->getNextWaiting($chamberId, $date);

        // Uses simplified layout
        $this->view('public/tv-board', [
            'title' => 'Reception Display Board',
            'queue' => $queue,
            'serving' => $serving,
            'next' => $next,
            'chamber_id' => $chamberId
        ], 'public'); // or a custom minimal layout
    }

    /**
     * AJAX Polling status JSON feed
     */
    public function apiStatus(): void {
        $chamberId = intval($_GET['chamber_id'] ?? 1);
        $date = date('Y-m-d');

        $serialModel = new Serial();
        $queue = $serialModel->getQueue($chamberId, $date);
        
        $serving = $serialModel->getCurrentServing($chamberId, $date);
        $next = $serialModel->getNextWaiting($chamberId, $date);

        // Estimate wait times
        $avgMinutes = 10; // default avg consultation time
        $waitingCount = 0;
        
        $waitingList = [];
        foreach ($queue as $item) {
            if ($item['status'] === 'waiting') {
                $waitingCount++;
                $waitingList[] = [
                    'serial_number' => $item['serial_number'],
                    'patient_name' => $item['patient_name'],
                    'token' => $item['token_number'],
                    'type' => $item['patient_type'],
                    'est_wait' => ($waitingCount * $avgMinutes) . " mins"
                ];
            }
        }

        $this->json([
            'serving' => $serving ? [
                'serial_number' => $serving['serial_number'],
                'patient_name' => $serving['patient_name'],
                'token' => $serving['token_number'],
                'called_at' => $serving['called_at']
            ] : null,
            'next' => $next ? [
                'serial_number' => $next['serial_number'],
                'patient_name' => $next['patient_name']
            ] : null,
            'waiting_count' => $waitingCount,
            'avg_wait_time' => ($waitingCount * $avgMinutes) . " mins",
            'queue_list' => $waitingList
        ]);
    }

    /**
     * Receptionist Queue Panel View
     */
    public function receptionPanel(): void {
        $chamberId = intval($_GET['chamber_id'] ?? 1);
        $date = date('Y-m-d');

        $serialModel = new Serial();
        $queue = $serialModel->getQueue($chamberId, $date);

        // Fetch max online appointments setting
        $db = $serialModel->getDb();
        $settingsStmt = $db->prepare("SELECT setting_value FROM queue_settings WHERE chamber_id = :chamber_id AND setting_key = 'max_online_appointments'");
        $settingsStmt->execute(['chamber_id' => $chamberId]);
        $maxOnline = $settingsStmt->fetchColumn();

        if ($maxOnline === false) {
            // Seed a default limit of 20 online appointments if it does not exist
            $maxOnline = 20;
            $insertStmt = $db->prepare("INSERT INTO queue_settings (chamber_id, setting_key, setting_value, description, created_at, updated_at) 
                                        VALUES (:chamber_id, 'max_online_appointments', :val, 'Maximum acceptable online appointments limit', NOW(), NOW())");
            $insertStmt->execute(['chamber_id' => $chamberId, 'val' => $maxOnline]);
        }

        $this->view('reception/queue', [
            'title' => 'Live Queue Control',
            'queue' => $queue,
            'max_online' => intval($maxOnline),
            'chamber_id' => $chamberId
        ], 'app');
    }

    /**
     * Update Queue Settings (e.g. Max Online Appointments)
     */
    public function updateSettings(): void {
        $chamberId = intval($_POST['chamber_id'] ?? 1);
        $maxOnline = intval($_POST['max_online_appointments'] ?? 20);

        $serialModel = new Serial();
        $db = $serialModel->getDb();

        $updateStmt = $db->prepare("UPDATE queue_settings SET setting_value = :val, updated_at = NOW() WHERE chamber_id = :chamber_id AND setting_key = 'max_online_appointments'");
        $updateStmt->execute(['val' => $maxOnline, 'chamber_id' => $chamberId]);

        $this->redirectWithSuccess('reception/queue', 'Queue capacity configuration updated successfully.');
    }
}
