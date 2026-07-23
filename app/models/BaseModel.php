<?php

namespace App\Models;

use PDO;
use Exception;

abstract class BaseModel {
    protected static ?PDO $db = null;
    protected string $table;
    protected string $primaryKey = 'id';
    protected array $fillable = [];
    protected bool $useTimestamps = true;
    protected bool $softDelete = false;

    public function __construct() {
        if (self::$db === null) {
            $config = config('database');
            if (empty($config)) {
                throw new Exception("Database configuration not found.");
            }
            
            $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset={$config['charset']}";
            
            try {
                self::$db = new PDO($dsn, $config['username'], $config['password'], $config['options']);
            } catch (Exception $e) {
                throw new Exception("Database connection failed: " . $e->getMessage());
            }
        }
    }

    /**
     * Get the PDO database connection
     */
    public function getDb(): PDO {
        return self::$db;
    }

    /**
     * Find single record by ID
     */
    public function find($id) {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id";
        if ($this->softDelete) {
            $sql .= " AND deleted_at IS NULL";
        }
        $sql .= " LIMIT 1";

        $stmt = self::$db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    /**
     * Find record by column value
     */
    public function findBy(string $column, $value) {
        $sql = "SELECT * FROM {$this->table} WHERE {$column} = :val";
        if ($this->softDelete) {
            $sql .= " AND deleted_at IS NULL";
        }
        $sql .= " LIMIT 1";

        $stmt = self::$db->prepare($sql);
        $stmt->execute(['val' => $value]);
        return $stmt->fetch() ?: null;
    }

    /**
     * Get all records from table
     */
    public function all(string $orderBy = 'id ASC'): array {
        $sql = "SELECT * FROM {$this->table}";
        if ($this->softDelete) {
            $sql .= " WHERE deleted_at IS NULL";
        }
        $sql .= " ORDER BY {$orderBy}";

        $stmt = self::$db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Insert a record
     */
    public function create(array $data) {
        // Filter fillable fields
        if (!empty($this->fillable)) {
            $data = array_intersect_key($data, array_flip($this->fillable));
        }

        if ($this->useTimestamps) {
            $now = date('Y-m-d H:i:s');
            $data['created_at'] = $now;
            $data['updated_at'] = $now;
        }

        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";

        $stmt = self::$db->prepare($sql);
        $stmt->execute($data);
        return self::$db->lastInsertId();
    }

    /**
     * Update a record by ID
     */
    public function update($id, array $data): bool {
        if (!empty($this->fillable)) {
            $data = array_intersect_key($data, array_flip($this->fillable));
        }

        if ($this->useTimestamps) {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }

        $fields = '';
        foreach (array_keys($data) as $key) {
            $fields .= "{$key} = :{$key}, ";
        }
        $fields = rtrim($fields, ', ');

        $sql = "UPDATE {$this->table} SET {$fields} WHERE {$this->primaryKey} = :id_val";
        if ($this->softDelete) {
            $sql .= " AND deleted_at IS NULL";
        }

        $stmt = self::$db->prepare($sql);
        $data['id_val'] = $id;
        return $stmt->execute($data);
    }

    /**
     * Delete a record by ID
     */
    public function delete($id): bool {
        if ($this->softDelete) {
            $sql = "UPDATE {$this->table} SET deleted_at = :deleted_at WHERE {$this->primaryKey} = :id";
            $stmt = self::$db->prepare($sql);
            return $stmt->execute([
                'id' => $id,
                'deleted_at' => date('Y-m-d H:i:s')
            ]);
        }

        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id";
        $stmt = self::$db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Execute manual raw queries with params
     */
    public function query(string $sql, array $params = []): array {
        $stmt = self::$db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Execute manual raw update/delete/insert
     */
    public function execute(string $sql, array $params = []): bool {
        $stmt = self::$db->prepare($sql);
        return $stmt->execute($params);
    }
}
