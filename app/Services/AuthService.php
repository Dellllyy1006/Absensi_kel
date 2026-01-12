<?php
/**
 * Authentication Service
 * 
 * Service untuk autentikasi pengguna
 * Mengikuti prinsip Single Responsibility (SRP) dan Dependency Inversion (DIP)
 */

namespace App\Services;

use App\Repositories\UserRepository;
use App\Repositories\SiswaRepository;
use Core\Interfaces\AuthServiceInterface;
use Core\Session;
use Core\Database;

class AuthService implements AuthServiceInterface
{
    private UserRepository $userRepository;
    private SiswaRepository $siswaRepository;
    private Session $session;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->siswaRepository = new SiswaRepository();
        $this->session = new Session();
    }

    /**
     * @inheritDoc
     */
    public function login(string $identifier, string $password)
    {
        // Find user by username or email
        $user = $this->userRepository->findByUsernameOrEmail($identifier);
        
        if (!$user) {
            return false;
        }

        // Verify password
        if (!$this->userRepository->verifyPassword($password, $user['password'])) {
            return false;
        }

        // Remove password from session data
        unset($user['password']);

        // Get siswa profile if role is siswa
        if ($user['role'] === 'siswa') {
            $profile = $this->siswaRepository->findByUserId($user['id']);
            if ($profile) {
                $user['profile'] = $profile;
            }
        }

        // Store user in session
        $this->session->set('user', $user);
        $this->session->regenerate();

        return $user;
    }

    /**
     * @inheritDoc
     */
    public function register(array $data)
    {
        // Validate required fields
        if (empty($data['username']) || empty($data['email']) || empty($data['password'])) {
            return false;
        }

        // Check if username or email already exists
        if ($this->userRepository->usernameExists($data['username'])) {
            return false;
        }

        if ($this->userRepository->emailExists($data['email'])) {
            return false;
        }

        // Start transaction
        $db = Database::getInstance();
        $db->beginTransaction();

        try {
            // Create user
            $userData = [
                'username' => $data['username'],
                'email' => $data['email'],
                'password' => $data['password'],
                'role' => $data['role'] ?? 'siswa'
            ];

            $userId = $this->userRepository->create($userData);

            if (!$userId) {
                $db->rollback();
                return false;
            }

            // Create siswa profile if role is siswa
            if (($data['role'] ?? 'siswa') === 'siswa') {
                $profileData = [
                    'user_id' => $userId,
                    'nis' => $data['nis'] ?? '',
                    'nama_lengkap' => $data['nama_lengkap'] ?? $data['username'],
                    'kelas' => $data['kelas'] ?? '',
                    'jurusan' => $data['jurusan'] ?? '',
                    'jenis_kelamin' => $data['jenis_kelamin'] ?? 'L',
                    'alamat' => $data['alamat'] ?? null,
                    'no_telepon' => $data['no_telepon'] ?? null
                ];

                $profileId = $this->siswaRepository->create($profileData);

                if (!$profileId) {
                    $db->rollback();
                    return false;
                }
            }

            $db->commit();
            return $userId;

        } catch (\Exception $e) {
            $db->rollback();
            return false;
        }
    }

    /**
     * @inheritDoc
     */
    public function logout(): void
    {
        $this->session->destroy();
    }

    /**
     * @inheritDoc
     */
    public function isAuthenticated(): bool
    {
        return $this->session->get('user') !== null;
    }

    /**
     * @inheritDoc
     */
    public function getCurrentUser(): ?array
    {
        return $this->session->get('user');
    }

    /**
     * Check if current user has specific role
     * 
     * @param string $role
     * @return bool
     */
    public function hasRole(string $role): bool
    {
        $user = $this->getCurrentUser();
        return $user && $user['role'] === $role;
    }

    /**
     * Update user profile in session
     * 
     * @return void
     */
    public function refreshSession(): void
    {
        $user = $this->getCurrentUser();
        if ($user) {
            $freshUser = $this->userRepository->find($user['id']);
            if ($freshUser) {
                unset($freshUser['password']);
                
                if ($freshUser['role'] === 'siswa') {
                    $profile = $this->siswaRepository->findByUserId($freshUser['id']);
                    if ($profile) {
                        $freshUser['profile'] = $profile;
                    }
                }
                
                $this->session->set('user', $freshUser);
            }
        }
    }
}
