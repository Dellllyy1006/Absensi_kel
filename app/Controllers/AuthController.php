<?php
/**
 * Auth Controller
 * 
 * Handle login, register, dan logout
 */

namespace App\Controllers;

use Core\Controller;
use App\Services\AuthService;

class AuthController extends Controller
{
    private AuthService $authService;

    public function __construct()
    {
        parent::__construct();
        $this->authService = new AuthService();
    }

    /**
     * Show login form
     */
    public function loginForm(): void
    {
        // Redirect if already logged in
        if ($this->authService->isAuthenticated()) {
            $this->redirect('/dashboard');
        }

        $this->view('auth/login', [
            'title' => 'Login - ' . APP_NAME
        ]);
    }

    /**
     * Process login
     */
    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/auth/login');
        }

        $identifier = $this->getPost('identifier');
        $password = $this->getPost('password');

        // Validate input
        if (empty($identifier) || empty($password)) {
            $this->setFlash('error', 'Username/Email dan Password harus diisi');
            $this->redirect('/auth/login');
        }

        // Attempt login
        $user = $this->authService->login($identifier, $password);

        if ($user) {
            $this->setFlash('success', 'Selamat datang, ' . $user['username'] . '!');
            $this->redirect('/dashboard');
        } else {
            $this->setFlash('error', 'Username/Email atau Password salah');
            $this->redirect('/auth/login');
        }
    }

    /**
     * Show register form
     */
    public function registerForm(): void
    {
        // Redirect if already logged in
        if ($this->authService->isAuthenticated()) {
            $this->redirect('/dashboard');
        }

        $this->view('auth/register', [
            'title' => 'Register - ' . APP_NAME
        ]);
    }

    /**
     * Process registration
     */
    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/auth/register');
        }

        $data = [
            'username' => trim($this->getPost('username')),
            'email' => trim($this->getPost('email')),
            'password' => $this->getPost('password'),
            'confirm_password' => $this->getPost('confirm_password'),
            'nis' => trim($this->getPost('nis')),
            'nama_lengkap' => trim($this->getPost('nama_lengkap')),
            'kelas' => trim($this->getPost('kelas')),
            'jurusan' => trim($this->getPost('jurusan')),
            'jenis_kelamin' => $this->getPost('jenis_kelamin'),
            'role' => 'siswa'
        ];

        // Validate required fields
        $errors = $this->validateRegistration($data);

        if (!empty($errors)) {
            $this->setFlash('error', implode('<br>', $errors));
            $this->redirect('/auth/register');
        }

        // Attempt registration
        $userId = $this->authService->register($data);

        if ($userId) {
            $this->setFlash('success', 'Registrasi berhasil! Silakan login.');
            $this->redirect('/auth/login');
        } else {
            $this->setFlash('error', 'Registrasi gagal. Username atau Email mungkin sudah digunakan.');
            $this->redirect('/auth/register');
        }
    }

    /**
     * Logout
     */
    public function logout(): void
    {
        $this->authService->logout();
        $this->setFlash('success', 'Anda telah berhasil logout');
        $this->redirect('/auth/login');
    }

    /**
     * Validate registration data
     * 
     * @param array $data
     * @return array Errors
     */
    private function validateRegistration(array $data): array
    {
        $errors = [];

        if (empty($data['username'])) {
            $errors[] = 'Username harus diisi';
        } elseif (strlen($data['username']) < 4) {
            $errors[] = 'Username minimal 4 karakter';
        }

        if (empty($data['email'])) {
            $errors[] = 'Email harus diisi';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Format email tidak valid';
        }

        if (empty($data['password'])) {
            $errors[] = 'Password harus diisi';
        } elseif (strlen($data['password']) < 6) {
            $errors[] = 'Password minimal 6 karakter';
        }

        if ($data['password'] !== $data['confirm_password']) {
            $errors[] = 'Konfirmasi password tidak cocok';
        }

        if (empty($data['nis'])) {
            $errors[] = 'NIS harus diisi';
        }

        if (empty($data['nama_lengkap'])) {
            $errors[] = 'Nama lengkap harus diisi';
        }

        if (empty($data['kelas'])) {
            $errors[] = 'Kelas harus diisi';
        }

        if (empty($data['jurusan'])) {
            $errors[] = 'Jurusan harus diisi';
        }

        return $errors;
    }
}
