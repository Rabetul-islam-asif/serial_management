<?php

namespace App\Controllers;

use App\Models\Medicine;
use App\Models\Visit;
use App\Models\Prescription;
use App\Models\Serial;
use App\Helpers\PdfHelper;
use Exception;

class PrescriptionController extends BaseController {

    /**
     * Search Medicine Autocomplete
     */
    public function searchMedicine(): void {
        $term = trim($_GET['q'] ?? '');
        if (strlen($term) < 1) {
            $this->json([]);
        }

        $medModel = new Medicine();
        $results = $medModel->autocomplete($term);
        $this->json($results);
    }

    /**
     * Toggle Favorite Medicine
     */
    public function addFavorite(): void {
        $id = intval($_POST['id'] ?? 0);
        if ($id <= 0) $this->json(['error' => 'Invalid ID'], 400);

        $medModel = new Medicine();
        $med = $medModel->find($id);

        if (!$med) $this->json(['error' => 'Medicine not found'], 404);

        $newFav = $med['is_favorite'] ? 0 : 1;
        $medModel->update($id, ['is_favorite' => $newFav]);
        
        $this->json(['success' => true, 'is_favorite' => $newFav]);
    }

    /**
     * Show Prescription Editor Form
     */
    public function create(): void {
        $serialId = intval($_GET['serial_id'] ?? 0);
        if ($serialId <= 0) {
            $this->redirectWithError('dashboard', 'Please select a patient from the queue.');
        }

        $serialModel = new Serial();
        $serial = $serialModel->find($serialId);
        
        // Find patient name
        $sql = "SELECT p.* FROM patients p 
                INNER JOIN appointments a ON a.patient_id = p.id 
                WHERE a.id = :app_id LIMIT 1";
        $patient = $serialModel->getDb()->prepare($sql);
        $patient->execute(['app_id' => $serial['appointment_id']]);
        $patientDetails = $patient->fetch();

        $medModel = new Medicine();
        $favorites = $medModel->getFavorites();

        $this->view('doctor/prescription-editor', [
            'title' => 'Write Prescription',
            'serial' => $serial,
            'patient' => $patientDetails,
            'favorites' => $favorites
        ]);
    }

    /**
     * Save Prescription
     */
    public function store(): void {
        $serialId = intval($_POST['serial_id'] ?? 0);
        $patientId = intval($_POST['patient_id'] ?? 0);
        $chamberId = intval($_POST['chamber_id'] ?? 1);
        
        $complaint = trim($_POST['chief_complaint'] ?? '');
        $diagnosis = trim($_POST['diagnosis'] ?? '');
        $instructions = trim($_POST['special_instructions'] ?? '');
        $notes = trim($_POST['doctor_notes'] ?? '');
        $nextVisit = $_POST['next_visit_date'] ?? null;
        
        $medicines = $_POST['medicines'] ?? []; // Array of {id, dosage, freq, dur}

        if ($patientId <= 0 || empty($medicines)) {
            $this->redirectWithError('doctor/prescription/new?serial_id=' . $serialId, 'Please add at least one medicine.');
        }

        $visitModel = new Visit();
        $db = $visitModel->getDb();
        $db->beginTransaction();

        try {
            // 1. Create Visit Record
            $visitId = $visitModel->create([
                'patient_id' => $patientId,
                'serial_id' => $serialId,
                'chamber_id' => $chamberId,
                'visit_date' => date('Y-m-d'),
                'chief_complaint' => $complaint,
                'diagnosis' => $diagnosis,
                'doctor_notes' => $notes,
                'next_visit_date' => empty($nextVisit) ? null : $nextVisit,
                'status' => 'completed'
            ]);

            // 2. Generate Prescription Number
            $prescCountSql = "SELECT COUNT(*) as cnt FROM prescriptions";
            $res = $db->query($prescCountSql)->fetch();
            $prescNum = "RX-" . date('ymd') . sprintf("%04d", ($res['cnt'] + 1));

            // 3. Create Prescription Record
            $prescModel = new Prescription();
            $prescId = $prescModel->create([
                'visit_id' => $visitId,
                'patient_id' => $patientId,
                'prescription_number' => $prescNum,
                'rx_date' => date('Y-m-d'),
                'special_instructions' => $instructions
            ]);

            // 4. Save Prescription Items
            $sort = 1;
            $medModel = new Medicine();
            foreach ($medicines as $med) {
                $sqlItem = "INSERT INTO prescription_items (prescription_id, medicine_id, dosage, frequency, duration, sort_order) 
                            VALUES (:presc_id, :med_id, :dosage, :freq, :dur, :sort)";
                $stmt = $db->prepare($sqlItem);
                $stmt->execute([
                    'presc_id' => $prescId,
                    'med_id' => intval($med['id']),
                    'dosage' => $med['dosage'],
                    'freq' => $med['frequency'],
                    'dur' => $med['duration'],
                    'sort' => $sort++
                ]);

                // Increment usage statistics
                $medModel->incrementUsage(intval($med['id']));
            }

            // 5. Complete Queue Token
            if ($serialId > 0) {
                $sqlQueue = "UPDATE serials SET status = 'completed', completed_at = NOW() WHERE id = :serial_id";
                $stmtQueue = $db->prepare($sqlQueue);
                $stmtQueue->execute(['serial_id' => $serialId]);

                $sqlApp = "UPDATE appointments SET status = 'completed', updated_at = NOW() 
                           WHERE id = (SELECT appointment_id FROM serials WHERE id = :serial_id)";
                $stmtApp = $db->prepare($sqlApp);
                $stmtApp->execute(['serial_id' => $serialId]);
            }

            $db->commit();
            
            // Redirect to print preview
            $this->redirect('doctor/prescription/print?id=' . $prescId);

        } catch (Exception $e) {
            $db->rollBack();
            $this->redirectWithError('doctor/prescription/new?serial_id=' . $serialId, 'Failed to save prescription: ' . $e->getMessage());
        }
    }

    /**
     * Print View
     */
    public function printView(): void {
        $id = intval($_GET['id'] ?? 0);
        if ($id <= 0) die('Invalid ID');

        $prescModel = new Prescription();
        $p = $prescModel->getFullDetails($id);

        if (!$p) die('Prescription not found');

        // Echo the raw print page directly
        echo PdfHelper::generateHtml($p);
        exit;
    }
}
