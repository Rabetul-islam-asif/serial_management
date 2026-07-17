<?php

namespace App\Models;

class Patient extends BaseModel {
    protected string $table = 'patients';
    protected array $fillable = [
        'name', 'phone', 'email', 'age', 'gender', 'blood_group', 'address', 'medical_notes'
    ];
    protected bool $softDelete = true;

    /**
     * Search patient by name or phone autocomplete
     */
    public function search(string $query): array {
        $sql = "SELECT * FROM {$this->table} 
                WHERE (name LIKE :query OR phone LIKE :query) 
                  AND deleted_at IS NULL 
                LIMIT 10";
        return $this->query($sql, ['query' => '%' . $query . '%']);
    }
}
