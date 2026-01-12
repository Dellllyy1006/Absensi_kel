<?php
/**
 * Authentication Service Interface
 * 
 * Interface untuk layanan autentikasi
 */

namespace Core\Interfaces;

interface AuthServiceInterface
{
    /**
     * Login user
     * 
     * @param string $username
     * @param string $password
     * @return array|false User data or false on failure
     */
    public function login(string $username, string $password);

    /**
     * Register new user
     * 
     * @param array $data
     * @return int|false User ID or false on failure
     */
    public function register(array $data);

    /**
     * Logout current user
     * 
     * @return void
     */
    public function logout(): void;

    /**
     * Check if user is authenticated
     * 
     * @return bool
     */
    public function isAuthenticated(): bool;

    /**
     * Get current authenticated user
     * 
     * @return array|null
     */
    public function getCurrentUser(): ?array;
}
