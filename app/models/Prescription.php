<?php

namespace App\Models;

class Prescription extends BaseModel {
    protected string $table = 'prescriptions';
    protected array $fillable = [
        'visit_id', 'patient_id', 'prescription_number', 'rx_date', 'special_instructions', 'pdf_path'
    ];

    /**
     * Get items (medicines) associated with a prescription
     */
    public function getItems(int $prescriptionId): array {
        $sql = "SELECT pi.*, m.name as medicine_name, m.generic_name, m.strength, m.type as medicine_type 
                FROM prescription_items pi
                INNER JOIN medicines m ON pi.medicine_id = m.id
                WHERE pi.prescription_id = :presc_id 
                ORDER BY pi.sort_order ASC";
        return $this->query($sql, ['presc_id' => $prescriptionId]);
    }

    /**
     * Get prescription summary by ID
     */
    public function getFullDetails(int $id) {
        $sql = "SELECT pr.*, v.chief_complaint, v.diagnosis, v.next_visit_date, 
                       p.name as patient_name, p.age as patient_age, p.gender as patient_gender, p.phone as patient_phone,
                       dp.name as doctor_name, dp.degree as doctor_degree, dp.specialization as doctor_spec, dp.bmdc_number as doctor_bmdc,
                       c.name as chamber_name, c.address as chamber_address
                FROM {$this->table} pr
                INNER JOIN visits v ON pr.visit_id = v.id
                INNER JOIN patients p ON pr.patient_id = p.id
                INNER JOIN chambers c ON v.chamber_id = c.id
                INNER JOIN doctor_profile dp ON c.doctor_id = dp.id
                WHERE pr.id = :id 
                LIMIT 1";
        
        $results = $this->query($sql, ['id' => $id]);
        if (empty($results)) {
            return null;
        }
        
        $prescription = $results[0];
        $prescription['items'] = $this->getItems($id);
        
        return $prescription;
    }
}
