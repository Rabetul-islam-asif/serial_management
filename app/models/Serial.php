<?php

namespace App\Models;

class Serial extends BaseModel {
    protected string $table = 'serials';
    protected array $fillable = [
        'appointment_id', 'chamber_id', 'serial_date', 'serial_number', 'queue_position',
        'patient_type', 'priority_level', 'status', 'called_at', 'started_at', 'completed_at',
        'hold_reason', 'missed_rejoin_after', 'original_position', 'is_rejoined', 'token_number', 'notes'
    ];

    /**
     * Ensure active queue serials auto-rollover to today's date if no serials exist for today
     */
    public function ensureTodayQueue(int $chamberId, string $date): void {
        if ($date === date('Y-m-d')) {
            $checkSql = "SELECT COUNT(*) as cnt FROM {$this->table} WHERE chamber_id = :chamber_id AND serial_date = :date";
            $res = $this->query($checkSql, ['chamber_id' => $chamberId, 'date' => $date]);
            if (($res[0]['cnt'] ?? 0) == 0) {
                // Auto-rollover active uncompleted serials from previous dates to today
                $rollSql = "UPDATE {$this->table} SET serial_date = :date WHERE chamber_id = :chamber_id AND status NOT IN ('completed', 'cancelled')";
                $this->execute($rollSql, ['chamber_id' => $chamberId, 'date' => $date]);
            }
        }
    }

    /**
     * Get queue list for a chamber on a date
     */
    public function getQueue(int $chamberId, string $date): array {
        $this->ensureTodayQueue($chamberId, $date);

        $sql = "SELECT s.*, p.name as patient_name, p.phone as patient_phone, p.age as patient_age, p.gender as patient_gender,
                       pr.pdf_path as prescription_path
                FROM {$this->table} s
                INNER JOIN appointments a ON s.appointment_id = a.id
                INNER JOIN patients p ON a.patient_id = p.id
                LEFT JOIN visits v ON s.id = v.serial_id
                LEFT JOIN prescriptions pr ON v.id = pr.visit_id
                WHERE s.chamber_id = :chamber_id 
                  AND s.serial_date = :date
                ORDER BY s.queue_position ASC";
                
        return $this->query($sql, [
            'chamber_id' => $chamberId,
            'date' => $date
        ]);
    }

    /**
     * Get currently serving patient
     */
    public function getCurrentServing(int $chamberId, string $date) {
        $sql = "SELECT s.*, p.name as patient_name, p.phone as patient_phone
                FROM {$this->table} s
                INNER JOIN appointments a ON s.appointment_id = a.id
                INNER JOIN patients p ON a.patient_id = p.id
                WHERE s.chamber_id = :chamber_id 
                  AND s.serial_date = :date
                  AND s.status IN ('called', 'in_consultation')
                LIMIT 1";
        $results = $this->query($sql, [
            'chamber_id' => $chamberId,
            'date' => $date
        ]);
        return !empty($results) ? $results[0] : null;
    }

    /**
     * Get next waiting patient
     */
    public function getNextWaiting(int $chamberId, string $date) {
        $sql = "SELECT s.*, p.name as patient_name
                FROM {$this->table} s
                INNER JOIN appointments a ON s.appointment_id = a.id
                INNER JOIN patients p ON a.patient_id = p.id
                WHERE s.chamber_id = :chamber_id 
                  AND s.serial_date = :date
                  AND s.status = 'waiting'
                ORDER BY s.queue_position ASC
                LIMIT 1";
        $results = $this->query($sql, [
            'chamber_id' => $chamberId,
            'date' => $date
        ]);
        return !empty($results) ? $results[0] : null;
    }

    /**
     * Get maximum serial number assigned today
     */
    public function getMaxSerialNumber(int $chamberId, string $date): int {
        $sql = "SELECT MAX(serial_number) as max_val FROM {$this->table} 
                WHERE chamber_id = :chamber_id AND serial_date = :date";
        $results = $this->query($sql, ['chamber_id' => $chamberId, 'date' => $date]);
        return (int)($results[0]['max_val'] ?? 0);
    }

    /**
     * Get maximum queue position today
     */
    public function getMaxQueuePosition(int $chamberId, string $date): int {
        $sql = "SELECT MAX(queue_position) as max_val FROM {$this->table} 
                WHERE chamber_id = :chamber_id AND serial_date = :date";
        $results = $this->query($sql, ['chamber_id' => $chamberId, 'date' => $date]);
        return (int)($results[0]['max_val'] ?? 0);
    }
}
