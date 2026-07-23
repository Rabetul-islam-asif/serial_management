<?php

namespace App\Controllers;

use App\Models\Serial;
use App\Models\QueueEngine;
use App\Models\DoctorProfile;
use App\Models\Chamber;

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

        // Fetch Doctor Profile & Chamber info
        $doctorModel = new DoctorProfile();
        $doctor = $doctorModel->find(1);

        $chamberModel = new Chamber();
        $chamber = $chamberModel->find($chamberId);

        $this->view('public/queue-board', [
            'title' => 'Live Queue Board',
            'queue' => $queue,
            'serving' => $serving,
            'next' => $next,
            'doctor' => $doctor,
            'chamber' => $chamber,
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

        $doctorModel = new DoctorProfile();
        $doctor = $doctorModel->find(1);

        $chamberModel = new Chamber();
        $chamber = $chamberModel->find($chamberId);

        $this->view('public/queue-board', [
            'title' => 'Reception Display Board',
            'queue' => $queue,
            'serving' => $serving,
            'next' => $next,
            'doctor' => $doctor,
            'chamber' => $chamber,
            'chamber_id' => $chamberId
        ], 'public');
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

        $doctorModel = new DoctorProfile();
        $doctor = $doctorModel->find(1);

        $chamberModel = new Chamber();
        $chamber = $chamberModel->find($chamberId);

        // Fetch custom consultation time settings for chamber
        $db = $serialModel->getDb();
        $stmtNew = $db->prepare("SELECT setting_value FROM queue_settings WHERE chamber_id = :chamber_id AND setting_key = 'avg_consultation_time'");
        $stmtNew->execute(['chamber_id' => $chamberId]);
        $avgNewTime = intval($stmtNew->fetchColumn() ?: 7);

        $stmtReport = $db->prepare("SELECT setting_value FROM queue_settings WHERE chamber_id = :chamber_id AND setting_key = 'avg_report_time'");
        $stmtReport->execute(['chamber_id' => $chamberId]);
        $avgReportTime = intval($stmtReport->fetchColumn() ?: 3);

        $fullQueueList = [];
        $waitingCount = 0;
        $cumulativeWaitMinutes = 0;

        foreach ($queue as $item) {
            $isServing = ($serving && $serving['id'] == $item['id']);
            $isNext = ($next && $next['id'] == $item['id']);

            $displayStatus = 'Serialized';
            $ewtStr = '00:00';

            if ($item['status'] === 'called' || $item['status'] === 'in_consultation' || $isServing) {
                $displayStatus = 'Running';
                $ewtStr = '00:00';
            } elseif ($isNext) {
                $displayStatus = 'Next';
                $ewtStr = '00:00';
            } elseif ($item['status'] === 'waiting') {
                $waitingCount++;
                if ($item['patient_type'] === 'report') {
                    $displayStatus = 'Report';
                } else {
                    $displayStatus = 'Serialized';
                }
                
                // Format EWT dynamically using patient-type duration
                $ewtStr = sprintf("%02d:00", $cumulativeWaitMinutes);
                $duration = ($item['patient_type'] === 'report') ? $avgReportTime : $avgNewTime;
                $cumulativeWaitMinutes += $duration;
            } elseif ($item['status'] === 'hold') {
                $displayStatus = 'On Hold';
                $ewtStr = '--:--';
            } elseif ($item['status'] === 'missed') {
                $displayStatus = 'Missed';
                $ewtStr = '--:--';
            } elseif ($item['status'] === 'completed') {
                $displayStatus = 'Completed';
                $ewtStr = 'Done';
            }

            if ($item['status'] !== 'cancelled') {
                $fullQueueList[] = [
                    'id' => $item['id'],
                    'serial_number' => $item['serial_number'],
                    'token' => $item['token_number'],
                    'patient_name' => $item['patient_name'],
                    'patient_type' => $item['patient_type'],
                    'status' => $item['status'],
                    'display_status' => $displayStatus,
                    'is_serving' => $isServing,
                    'is_next' => $isNext,
                    'est_wait' => $ewtStr
                ];
            }
        }

        $this->json([
            'doctor' => $doctor ? [
                'name' => $doctor['name'],
                'degree' => $doctor['degree'],
                'specialization' => $doctor['specialization'],
                'hospital' => $doctor['hospital'],
                'bmdc_number' => $doctor['bmdc_number'],
                'photo' => get_doctor_photo($doctor['photo'] ?? null)
            ] : null,
            'chamber' => $chamber ? [
                'name' => $chamber['name'],
                'address' => $chamber['address'],
                'phone' => $chamber['phone']
            ] : null,
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
            'avg_wait_time' => ($cumulativeWaitMinutes) . " mins",
            'avg_new_time' => $avgNewTime,
            'avg_report_time' => $avgReportTime,
            'queue_list' => $fullQueueList
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

        // Fetch queue settings (max_online_appointments, avg_consultation_time, avg_report_time)
        $db = $serialModel->getDb();
        $settingsStmt = $db->prepare("SELECT setting_key, setting_value FROM queue_settings WHERE chamber_id = :chamber_id");
        $settingsStmt->execute(['chamber_id' => $chamberId]);
        $rawSettings = $settingsStmt->fetchAll(\PDO::FETCH_KEY_PAIR);

        $maxOnline = intval($rawSettings['max_online_appointments'] ?? 20);
        $avgNewTime = intval($rawSettings['avg_consultation_time'] ?? 7);
        $avgReportTime = intval($rawSettings['avg_report_time'] ?? 3);

        // Seed missing settings if needed
        if (!isset($rawSettings['max_online_appointments'])) {
            $db->prepare("INSERT INTO queue_settings (chamber_id, setting_key, setting_value, description, created_at, updated_at) VALUES (:cid, 'max_online_appointments', '20', 'Max online appointments', NOW(), NOW())")->execute(['cid' => $chamberId]);
        }
        if (!isset($rawSettings['avg_consultation_time'])) {
            $db->prepare("INSERT INTO queue_settings (chamber_id, setting_key, setting_value, description, created_at, updated_at) VALUES (:cid, 'avg_consultation_time', '7', 'Avg consultation time for new patients in mins', NOW(), NOW())")->execute(['cid' => $chamberId]);
        }
        if (!isset($rawSettings['avg_report_time'])) {
            $db->prepare("INSERT INTO queue_settings (chamber_id, setting_key, setting_value, description, created_at, updated_at) VALUES (:cid, 'avg_report_time', '3', 'Avg review time for report patients in mins', NOW(), NOW())")->execute(['cid' => $chamberId]);
        }

        // Fetch upcoming advance appointments (tomorrow and beyond)
        $upcomingSql = "SELECT s.*, p.name as patient_name, p.phone as patient_phone, p.age as patient_age
                        FROM serials s
                        INNER JOIN appointments a ON s.appointment_id = a.id
                        INNER JOIN patients p ON a.patient_id = p.id
                        WHERE s.chamber_id = :chamber_id 
                          AND s.serial_date > :today
                          AND s.status NOT IN ('cancelled', 'completed')
                        ORDER BY s.serial_date ASC, s.serial_number ASC
                        LIMIT 30";
        $upcomingStmt = $db->prepare($upcomingSql);
        $upcomingStmt->execute(['chamber_id' => $chamberId, 'today' => $date]);
        $upcoming = $upcomingStmt->fetchAll();

        $this->view('reception/queue', [
            'title' => 'Live Queue Control',
            'queue' => $queue,
            'upcoming' => $upcoming,
            'max_online' => $maxOnline,
            'avg_new_time' => $avgNewTime,
            'avg_report_time' => $avgReportTime,
            'chamber_id' => $chamberId
        ], 'app');
    }

    /**
     * Get Upcoming Advance Appointments (AJAX)
     */
    public function getUpcomingAppointments(): void {
        $chamberId = intval($_GET['chamber_id'] ?? 1);
        $today = date('Y-m-d');
        
        $serialModel = new Serial();
        $db = $serialModel->getDb();
        
        $upcomingSql = "SELECT s.*, p.name as patient_name, p.phone as patient_phone, p.age as patient_age
                        FROM serials s
                        INNER JOIN appointments a ON s.appointment_id = a.id
                        INNER JOIN patients p ON a.patient_id = p.id
                        WHERE s.chamber_id = :chamber_id 
                          AND s.serial_date > :today
                          AND s.status NOT IN ('cancelled', 'completed')
                        ORDER BY s.serial_date ASC, s.serial_number ASC";
        $upcomingStmt = $db->prepare($upcomingSql);
        $upcomingStmt->execute(['chamber_id' => $chamberId, 'today' => $today]);
        
        $this->json($upcomingStmt->fetchAll());
    }

    /**
     * Update Queue Settings (Max Online Appointments & Patient Consultation Durations)
     */
    public function updateSettings(): void {
        $chamberId = intval($_POST['chamber_id'] ?? 1);
        $maxOnline = intval($_POST['max_online_appointments'] ?? 20);
        $avgNewTime = intval($_POST['avg_consultation_time'] ?? 7);
        $avgReportTime = intval($_POST['avg_report_time'] ?? 3);

        $serialModel = new Serial();
        $db = $serialModel->getDb();

        $upsertSql = "INSERT INTO queue_settings (chamber_id, setting_key, setting_value, created_at, updated_at) 
                      VALUES (:cid, :key, :val, NOW(), NOW()) 
                      ON DUPLICATE KEY UPDATE setting_value = :val, updated_at = NOW()";

        $db->prepare($upsertSql)->execute(['cid' => $chamberId, 'key' => 'max_online_appointments', 'val' => $maxOnline]);
        $db->prepare($upsertSql)->execute(['cid' => $chamberId, 'key' => 'avg_consultation_time', 'val' => $avgNewTime]);
        $db->prepare($upsertSql)->execute(['cid' => $chamberId, 'key' => 'avg_report_time', 'val' => $avgReportTime]);

        $this->redirectWithSuccess('reception/queue', 'Queue timing and patient consultation configuration updated successfully.');
    }
}
