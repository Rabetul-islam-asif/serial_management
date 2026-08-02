<?php

namespace App\Models;

class Patient extends BaseModel {
    protected string $table = 'patients';
    protected array $fillable = [
        'name', 'phone', 'email', 'age', 'gender', 'blood_group', 'address', 'medical_notes',
        'phone_account_id', 'patient_uid'
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

    /**
     * Generate patient UID like 'P-FIRSTNAME-0042'
     */
    public function generatePatientUid(string $name): string {
        $firstName = strtoupper(explode(' ', trim($name))[0]);
        $firstName = preg_replace('/[^A-Z]/', '', $firstName);
        if (empty($firstName)) {
            $firstName = 'UNKNOWN';
        }

        $sql = "SELECT patient_uid FROM {$this->table} WHERE patient_uid LIKE :prefix ORDER BY id DESC LIMIT 1";
        $results = $this->query($sql, ['prefix' => "P-{$firstName}-%"]);

        $nextId = 1;
        if (!empty($results)) {
            $lastUid = $results[0]['patient_uid'];
            $parts = explode('-', $lastUid);
            $lastId = (int)end($parts);
            $nextId = $lastId + 1;
        }

        return "P-{$firstName}-" . str_pad($nextId, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Find patients by phone account
     */
    public function findByPhoneAccount(int $phoneAccountId): array {
        $sql = "SELECT * FROM {$this->table} WHERE phone_account_id = :id AND deleted_at IS NULL ORDER BY name ASC";
        return $this->query($sql, ['id' => $phoneAccountId]);
    }

    /**
     * Find patient by UID
     */
    public function findByUid(string $uid): ?array {
        return $this->findBy('patient_uid', $uid);
    }
}
