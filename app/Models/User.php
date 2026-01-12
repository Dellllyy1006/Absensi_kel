<?php
/**
 * User Model
 * 
 * Model untuk tabel users
 */

namespace App\Models;

use Core\Model;

class User extends Model
{
    protected string $table = 'users';
    
    protected array $fillable = [
        'username',
        'email',
        'password',
        'role'
    ];

    /**
     * Find user by email
     * 
     * @param string $email
     * @return array|null
     */
    public function findByEmail(string $email): ?array
    {
        return $this->findBy('email', $email);
    }

    /**
     * Find user by username
     * 
     * @param string $username
     * @return array|null
     */
    public function findByUsername(string $username): ?array
    {
        return $this->findBy('username', $username);
    }

    /**
     * Find user by username or email
     * 
     * @param string $identifier
     * @return array|null
     */
    public function findByUsernameOrEmail(string $identifier): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE username = ? OR email = ?";
        $stmt = $this->db->query($sql, [$identifier, $identifier]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Check if username exists
     * 
     * @param string $username
     * @return bool
     */
    public function usernameExists(string $username): bool
    {
        return $this->findByUsername($username) !== null;
    }

    /**
     * Check if email exists
     * 
     * @param string $email
     * @return bool
     */
    public function emailExists(string $email): bool
    {
        return $this->findByEmail($email) !== null;
    }

    /**
     * Get all users by role
     * 
     * @param string $role
     * @return array
     */
    public function findByRole(string $role): array
    {
        return $this->findAll(['role' => $role]);
    }
}
