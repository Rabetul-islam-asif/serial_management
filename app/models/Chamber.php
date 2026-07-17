<?php

namespace App\Models;

class Chamber extends BaseModel {
    protected string $table = 'chambers';
    protected array $fillable = [
        'doctor_id', 'name', 'address', 'phone', 'google_map_url', 'is_active', 'sort_order'
    ];

    /**
     * Get active chambers for a doctor
     */
    public function getActiveChambers(int $doctorId): array {
        $sql = "SELECT * FROM {$this->table} 
                WHERE doctor_id = :doctor_id 
                  AND is_active = 1 
                ORDER BY sort_order ASC";
        return $this->query($sql, ['doctor_id' => $doctorId]);
    }

    /**
     * Get schedules for a chamber
     */
    public function getSchedules(int $chamberId): array {
        $sql = "SELECT * FROM chamber_schedules 
                WHERE chamber_id = :chamber_id 
                  AND is_active = 1 
                ORDER BY day_of_week ASC";
        return $this->query($sql, ['chamber_id' => $chamberId]);
    }
}
