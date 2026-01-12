<?php
/**
 * User Repository
 * 
 * Repository untuk operasi data user
 * Mengikuti prinsip Single Responsibility (SRP)
 */

namespace App\Repositories;

use App\Models\User;
use Core\Interfaces\RepositoryInterface;

class UserRepository implements RepositoryInterface
{
    private User $model;

    public function __construct()
    {
        $this->model = new User();
    }

    /**
     * @inheritDoc
     */
    public function find(int $id): ?array
    {
        return $this->model->find($id);
    }

    /**
     * @inheritDoc
     */
    public function findAll(array $conditions = []): array
    {
        return $this->model->findAll($conditions);
    }

    /**
     * @inheritDoc
     */
    public function create(array $data)
    {
        // Hash password before saving
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        return $this->model->create($data);
    }

    /**
     * @inheritDoc
     */
    public function update(int $id, array $data): bool
    {
        // Hash password if being updated
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        return $this->model->update($id, $data);
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id): bool
    {
        return $this->model->delete($id);
    }

    /**
     * Find user by email
     * 
     * @param string $email
     * @return array|null
     */
    public function findByEmail(string $email): ?array
    {
        return $this->model->findByEmail($email);
    }

    /**
     * Find user by username
     * 
     * @param string $username
     * @return array|null
     */
    public function findByUsername(string $username): ?array
    {
        return $this->model->findByUsername($username);
    }

    /**
     * Find user by username or email
     * 
     * @param string $identifier
     * @return array|null
     */
    public function findByUsernameOrEmail(string $identifier): ?array
    {
        return $this->model->findByUsernameOrEmail($identifier);
    }

    /**
     * Check if username exists
     * 
     * @param string $username
     * @return bool
     */
    public function usernameExists(string $username): bool
    {
        return $this->model->usernameExists($username);
    }

    /**
     * Check if email exists
     * 
     * @param string $email
     * @return bool
     */
    public function emailExists(string $email): bool
    {
        return $this->model->emailExists($email);
    }

    /**
     * Get all users by role
     * 
     * @param string $role
     * @return array
     */
    public function findByRole(string $role): array
    {
        return $this->model->findByRole($role);
    }

    /**
     * Verify password
     * 
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}
