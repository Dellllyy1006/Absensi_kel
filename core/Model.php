<?php
/**
 * Base Model
 * 
 * Menyediakan operasi database umum untuk model
 * Mengikuti prinsip Single Responsibility (SRP)
 */

namespace Core;

abstract class Model
{
    protected Database $db;
    protected string $table;
    protected string $primaryKey = 'id';
    protected array $fillable = [];

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Find record by ID
     * 
     * @param int $id
     * @return array|null
     */
    public function find(int $id): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?";
        $stmt = $this->db->query($sql, [$id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Find all records
     * 
     * @param array $conditions
     * @param string $orderBy
     * @return array
     */
    public function findAll(array $conditions = [], string $orderBy = ''): array
    {
        $sql = "SELECT * FROM {$this->table}";
        $params = [];

        if (!empty($conditions)) {
            $where = [];
            foreach ($conditions as $column => $value) {
                $where[] = "{$column} = ?";
                $params[] = $value;
            }
            $sql .= " WHERE " . implode(" AND ", $where);
        }

        if (!empty($orderBy)) {
            $sql .= " ORDER BY {$orderBy}";
        }

        $stmt = $this->db->query($sql, $params);
        return $stmt->fetchAll();
    }

    /**
     * Create new record
     * 
     * @param array $data
     * @return int|false
     */
    public function create(array $data)
    {
        // Filter only fillable fields
        $data = array_intersect_key($data, array_flip($this->fillable));
        
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        
        try {
            $this->db->query($sql, array_values($data));
            return (int) $this->db->lastInsertId();
        } catch (\PDOException $e) {
            return false;
        }
    }

    /**
     * Update record
     * 
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        // Filter only fillable fields
        $data = array_intersect_key($data, array_flip($this->fillable));
        
        $set = [];
        $params = [];
        
        foreach ($data as $column => $value) {
            $set[] = "{$column} = ?";
            $params[] = $value;
        }
        
        $params[] = $id;
        
        $sql = "UPDATE {$this->table} SET " . implode(', ', $set) . " WHERE {$this->primaryKey} = ?";
        
        try {
            $this->db->query($sql, $params);
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }

    /**
     * Delete record
     * 
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?";
        
        try {
            $this->db->query($sql, [$id]);
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }

    /**
     * Count records
     * 
     * @param array $conditions
     * @return int
     */
    public function count(array $conditions = []): int
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        $params = [];

        if (!empty($conditions)) {
            $where = [];
            foreach ($conditions as $column => $value) {
                $where[] = "{$column} = ?";
                $params[] = $value;
            }
            $sql .= " WHERE " . implode(" AND ", $where);
        }

        $stmt = $this->db->query($sql, $params);
        $result = $stmt->fetch();
        return (int) $result['count'];
    }

    /**
     * Find by specific column
     * 
     * @param string $column
     * @param mixed $value
     * @return array|null
     */
    public function findBy(string $column, $value): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$column} = ?";
        $stmt = $this->db->query($sql, [$value]);
        $result = $stmt->fetch();
        return $result ?: null;
    }
}
