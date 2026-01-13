<?php
/**
 * Profile Controller
 * 
 * Handle profile view dan edit
 */

namespace App\Controllers;

use Core\Controller;
use App\Repositories\SiswaRepository;
use App\Repositories\UserRepository;
use App\Services\QRCodeService;
use App\Services\AuthService;

class ProfileController extends Controller
{
    private SiswaRepository $siswaRepository;
    private UserRepository $userRepository;
    private QRCodeService $qrService;
    private AuthService $authService;

    public function __construct()
    {
        parent::__construct();
        $this->siswaRepository = new SiswaRepository();
        $this->userRepository = new UserRepository();
        $this->qrService = new QRCodeService();
        $this->authService = new AuthService();
    }

    /**
     * View profile
     */
    public function index(): void
    {
        $this->requireAuth();

        $user = $this->session->get('user');

        if ($user['role'] === 'siswa') {
            $profile = $this->siswaRepository->getFullProfile($user['id']);
            
            $this->render('profile/view', [
                'title' => 'Profil Saya - ' . APP_NAME,
                'profile' => $profile,
                'user' => $user
            ]);
        } else {
            // Admin profile view
            $this->render('profile/admin', [
                'title' => 'Profil Admin - ' . APP_NAME,
                'user' => $user
            ]);
        }
    }

    /**
     * Edit profile form
     */
    public function edit(): void
    {
        $this->requireAuth();
        
        $user = $this->session->get('user');
        $profile = null;

        if ($user['role'] === 'siswa') {
            $profile = $this->siswaRepository->getFullProfile($user['id']);
        }

        $this->render('profile/edit', [
            'title' => 'Edit Profil - ' . APP_NAME,
            'profile' => $profile,
            'user' => $user
        ]);
    }

    /**
     * Update profile
     */
    public function update(): void
    {
        $this->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/profile');
        }

        $user = $this->session->get('user');

        if ($user['role'] === 'siswa') {
            $this->updateSiswaProfile($user);
        } else {
            $this->updateAdminProfile($user);
        }
    }

    /**
     * Update siswa profile
     */
    private function updateSiswaProfile(array $user): void
    {
        $profile = $this->siswaRepository->findByUserId($user['id']);

        if (!$profile) {
            $this->setFlash('error', 'Profil tidak ditemukan');
            $this->redirect('/profile');
        }

        // Handle photo upload
        $foto = $profile['foto'];
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = $this->handlePhotoUpload($_FILES['foto'], $profile['nis']);
            if ($uploadResult) {
                $foto = $uploadResult;
            }
        }

        // Update profile data
        $profileData = [
            'nama_lengkap' => trim($this->getPost('nama_lengkap')),
            'alamat' => trim($this->getPost('alamat')),
            'no_telepon' => trim($this->getPost('no_telepon')),
            'foto' => $foto
        ];

        $success = $this->siswaRepository->update($profile['id'], $profileData);

        // Update user account data (email, username, password)
        $userData = [];
        
        // Email update
        $newEmail = trim($this->getPost('email'));
        if ($newEmail !== $user['email']) {
            if (!$this->userRepository->emailExists($newEmail)) {
                $userData['email'] = $newEmail;
            } else {
                $this->setFlash('error', 'Email sudah digunakan');
                $this->redirect('/profile/edit');
            }
        }

        // Username update
        $newUsername = trim($this->getPost('username'));
        if (!empty($newUsername) && $newUsername !== $user['username']) {
            if (!$this->userRepository->usernameExists($newUsername)) {
                $userData['username'] = $newUsername;
            } else {
                $this->setFlash('error', 'Username sudah digunakan');
                $this->redirect('/profile/edit');
            }
        }

        // Password update
        $newPassword = $this->getPost('password');
        if (!empty($newPassword)) {
            if (strlen($newPassword) >= 6) {
                $userData['password'] = $newPassword;
            } else {
                $this->setFlash('error', 'Password minimal 6 karakter');
                $this->redirect('/profile/edit');
            }
        }

        if (!empty($userData)) {
            $this->userRepository->update($user['id'], $userData);
        }

        if ($success || !empty($userData)) {
            $this->authService->refreshSession();
            $this->setFlash('success', 'Profil berhasil diperbarui');
        } else {
            $this->setFlash('error', 'Tidak ada perubahan yang disimpan');
        }

        $this->redirect('/profile');
    }

    /**
     * Update admin profile
     */
    private function updateAdminProfile(array $user): void
    {
        $data = [];
        
        $newEmail = trim($this->getPost('email'));
        if ($newEmail !== $user['email']) {
            if (!$this->userRepository->emailExists($newEmail)) {
                $data['email'] = $newEmail;
            }
        }

        $newPassword = $this->getPost('password');
        if (!empty($newPassword)) {
            if (strlen($newPassword) >= 6) {
                $data['password'] = $newPassword;
            }
        }

        if (!empty($data)) {
            $success = $this->userRepository->update($user['id'], $data);
            
            if ($success) {
                $this->authService->refreshSession();
                $this->setFlash('success', 'Profil berhasil diperbarui');
            } else {
                $this->setFlash('error', 'Gagal memperbarui profil');
            }
        }

        $this->redirect('/profile');
    }

    /**
     * Handle photo upload
     */
    private function handlePhotoUpload(array $file, string $nis): ?string
    {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxSize = 2 * 1024 * 1024; // 2MB

        if (!in_array($file['type'], $allowedTypes)) {
            return null;
        }

        if ($file['size'] > $maxSize) {
            return null;
        }

        // Ensure directory exists
        if (!is_dir(PHOTO_PATH)) {
            mkdir(PHOTO_PATH, 0755, true);
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'photo_' . $nis . '_' . time() . '.' . $extension;
        $destination = PHOTO_PATH . $filename;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return $filename;
        }

        return null;
    }

    /**
     * Generate personal QR code
     */
    public function generateQR(): void
    {
        $this->requireAuth();

        $user = $this->session->get('user');

        if ($user['role'] !== 'siswa') {
            $this->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $profile = $user['profile'] ?? $this->siswaRepository->findByUserId($user['id']);
        
        if (!$profile) {
             $this->json(['success' => false, 'message' => 'Profil siswa tidak ditemukan'], 404);
        }

        $qrPath = $this->qrService->generateSiswaQR($profile['id'], $profile['nis']);

        if ($qrPath) {
            // Save to database
            $this->siswaRepository->update($profile['id'], ['qr_code' => $qrPath]);
            
            $this->authService->refreshSession();
            $this->json([
                'success' => true,
                'message' => 'QR Code berhasil dibuat',
                'qr_path' => $qrPath
            ]);
        } else {
            $this->json(['success' => false, 'message' => 'Gagal membuat QR Code'], 500);
        }
    }

    /**
     * View siswa profile by admin
     */
    public function viewSiswa(): void
    {
        $this->requireRole('admin');

        $id = $this->getQuery('id');
        
        if (!$id) {
            $this->setFlash('error', 'ID siswa tidak valid');
            $this->redirect('/dashboard');
        }

        $profile = $this->siswaRepository->find($id);
        
        if (!$profile) {
            $this->setFlash('error', 'Siswa tidak ditemukan');
            $this->redirect('/dashboard');
        }

        $this->render('profile/view_siswa', [
            'title' => 'Profil Siswa - ' . APP_NAME,
            'profile' => $profile
        ]);
    }
}
