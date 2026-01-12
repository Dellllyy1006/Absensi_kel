<?php
/**
 * Session Management Class
 * 
 * Mengelola session untuk autentikasi
 * Mengikuti prinsip Single Responsibility (SRP)
 */

namespace Core;

class Session
{
    /**
     * Start session if not already started
     */
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Set session value
     * 
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Get session value
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Check if session key exists
     * 
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Remove session value
     * 
     * @param string $key
     * @return void
     */
    public function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    /**
     * Get and remove flash message
     * 
     * @return array|null
     */
    public function getFlash(): ?array
    {
        $flash = $this->get('flash');
        $this->remove('flash');
        return $flash;
    }

    /**
     * Destroy session
     * 
     * @return void
     */
    public function destroy(): void
    {
        session_unset();
        session_destroy();
    }

    /**
     * Regenerate session ID
     * 
     * @return bool
     */
    public function regenerate(): bool
    {
        return session_regenerate_id(true);
    }
}
