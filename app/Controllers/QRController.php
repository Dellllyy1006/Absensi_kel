<?php
/**
 * QR Controller
 * 
 * Handle QR code generation and session management
 */

namespace App\Controllers;

use Core\Controller;
use App\Services\QRCodeService;

class QRController extends Controller
{
    private QRCodeService $qrService;

    public function __construct()
    {
        parent::__construct();
        $this->qrService = new QRCodeService();
    }

    /**
     * QR session management page
     */
    public function index(): void
    {
        $this->requireRole('admin');

        $sessions = $this->qrService->getTodaySessions();
        $activeSession = $this->qrService->getActiveSession();

        $this->render('qr/index', [
            'title' => 'Sesi QR Absensi - ' . APP_NAME,
            'sessions' => $sessions,
            'activeSession' => $activeSession
        ]);
    }

    /**
     * Create new QR session
     */
    public function create(): void
    {
        $this->requireRole('admin');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/qr');
        }

        $waktuMulai = $this->getPost('waktu_mulai');
        $waktuSelesai = $this->getPost('waktu_selesai');

        if (empty($waktuMulai) || empty($waktuSelesai)) {
            $this->setFlash('error', 'Waktu mulai dan selesai harus diisi');
            $this->redirect('/qr');
        }

        $result = $this->qrService->createSession($waktuMulai, $waktuSelesai);

        if ($result) {
            $this->setFlash('success', 'Sesi QR berhasil dibuat');
        } else {
            $this->setFlash('error', 'Gagal membuat sesi QR. Pastikan koneksi internet aktif untuk generate QR.');
        }

        $this->redirect('/qr');
    }

    /**
     * Deactivate QR session
     */
    public function deactivate(): void
    {
        $this->requireRole('admin');

        $id = $this->getQuery('id');

        if (!$id) {
            $this->setFlash('error', 'ID sesi tidak valid');
            $this->redirect('/qr');
        }

        $success = $this->qrService->deactivateSession((int) $id);

        if ($success) {
            $this->setFlash('success', 'Sesi berhasil dinonaktifkan');
        } else {
            $this->setFlash('error', 'Gagal menonaktifkan sesi');
        }

        $this->redirect('/qr');
    }

    /**
     * Display QR code for session
     */
    public function display(): void
    {
        $this->requireRole('admin');

        $sessionCode = $this->getQuery('code');

        if (!$sessionCode) {
            $this->setFlash('error', 'Kode sesi tidak valid');
            $this->redirect('/qr');
        }

        $this->render('qr/display', [
            'title' => 'Display QR - ' . APP_NAME,
            'sessionCode' => $sessionCode,
            'qrPath' => 'session_' . $sessionCode . '.png'
        ]);
    }

    /**
     * Get active session status (AJAX)
     */
    public function status(): void
    {
        $activeSession = $this->qrService->getActiveSession();

        $this->json([
            'active' => $activeSession !== null,
            'session' => $activeSession
        ]);
    }
}
