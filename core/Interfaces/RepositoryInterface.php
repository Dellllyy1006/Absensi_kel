<?php
/**
 * Repository Interface
 * 
 * Interface dasar untuk semua repository
 * Mengikuti prinsip Interface Segregation (ISP)
 */

namespace Core\Interfaces;

interface RepositoryInterface
{
    /**
     * Find record by ID
     * 
     * @param int $id
     * @return array|null
     */
    public function find(int $id): ?array;

    /**
     * Find all records
     * 
     * @param array $conditions
     * @return array
     */
    public function findAll(array $conditions = []): array;

    /**
     * Create new record
     * 
     * @param array $data
     * @return int|false Last insert ID or false on failure
     */
    public function create(array $data);

    /**
     * Update existing record
     * 
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool;

    /**
     * Delete record by ID
     * 
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}
