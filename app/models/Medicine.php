<?php

namespace App\Models;

class Medicine extends BaseModel {
    protected string $table = 'medicines';
    protected array $fillable = [
        'name', 'generic_name', 'type', 'strength', 'manufacturer', 'is_favorite', 'usage_count'
    ];

    /**
     * Search medicine by name autocomplete
     */
    public function autocomplete(string $term): array {
        $sql = "SELECT * FROM {$this->table} 
                WHERE name LIKE :term OR generic_name LIKE :term 
                ORDER BY is_favorite DESC, usage_count DESC, name ASC 
                LIMIT 15";
        return $this->query($sql, ['term' => '%' . $term . '%']);
    }

    /**
     * Get list of favorite medicines
     */
    public function getFavorites(): array {
        return $this->query("SELECT * FROM {$this->table} WHERE is_favorite = 1 ORDER BY name ASC");
    }

    /**
     * Increment usage statistic count
     */
    public function incrementUsage(int $id): bool {
        $sql = "UPDATE {$this->table} SET usage_count = usage_count + 1 WHERE id = :id";
        return $this->execute($sql, ['id' => $id]);
    }
}
