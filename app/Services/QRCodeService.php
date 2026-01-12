<?php
/**
 * QR Code Service
 * 
 * Service untuk generate dan validasi QR Code
 * Mengikuti prinsip Single Responsibility (SRP)
 */

namespace App\Services;

use Core\Interfaces\QRServiceInterface;
use App\Models\QRSession;
use Core\Session;

/**
 * QR Code Service
 * 
 * Menangani pembuatan dan validasi QR Code
 */
class QRCodeService implements QRServiceInterface
{
    private QRSession $qrSessionModel;
    private Session $session;
    private string $qrCodePath;
    
    public function __construct()
    {
        $this->qrSessionModel = new QRSession();
        $this->session = new Session();
        $this->qrCodePath = QRCODE_PATH;
        
        // Pastikan direktori ada
        if (!is_dir($this->qrCodePath)) {
            mkdir($this->qrCodePath, 0755, true);
        }
    }
    
    /**
     * Generate QR Code image
     */
    public function generate(string $data, string $filename): bool
    {
        $filepath = $this->qrCodePath . $filename;
        
        // Try using GD library first
        if (function_exists('imagecreatetruecolor')) {
            try {
                require_once __DIR__ . '/../../vendor/phpqrcode/qrlib.php';
                \QRcode::png($data, $filepath, QR_ECLEVEL_M, 10, 2);
                return file_exists($filepath);
            } catch (\Exception $e) {
                // Fall through to API method
            }
        }
        
        // Fallback: Use Google Charts API
        $size = '300x300';
        $url = 'https://chart.googleapis.com/chart?cht=qr&chs=' . $size . '&chl=' . urlencode($data) . '&choe=UTF-8';
        
        $imageData = @file_get_contents($url);
        if ($imageData !== false) {
            file_put_contents($filepath, $imageData);
            return file_exists($filepath);
        }
        
        // Final fallback: Use goqr.me API
        $url2 = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($data);
        $imageData = @file_get_contents($url2);
        if ($imageData !== false) {
            file_put_contents($filepath, $imageData);
            return file_exists($filepath);
        }
        
        return false;
    }
    
    /**
     * Validate QR Code data untuk absensi
     */
    public function validate(string $data): array
    {
        // Parse QR data
        $qrData = json_decode($data, true);
        
        if (!$qrData || !isset($qrData['session_code'])) {
            return [
                'valid' => false,
                'message' => 'Format QR Code tidak valid'
            ];
        }
        
        $sessionCode = $qrData['session_code'];
        
        // Cek apakah sesi valid
        $session = $this->qrSessionModel->findByCode($sessionCode);
        
        if (!$session) {
            return [
                'valid' => false,
                'message' => 'Sesi absensi tidak ditemukan'
            ];
        }
        
        // Cek apakah sesi masih aktif
        if (!$session['is_active']) {
            return [
                'valid' => false,
                'message' => 'Sesi absensi sudah tidak aktif'
            ];
        }
        
        // Cek apakah masih dalam waktu sesi
        $now = date('H:i:s');
        if ($now < $session['waktu_mulai'] || $now > $session['waktu_selesai']) {
            return [
                'valid' => false,
                'message' => 'Diluar waktu sesi absensi'
            ];
        }
        
        // Cek tanggal
        if ($session['tanggal'] !== date('Y-m-d')) {
            return [
                'valid' => false,
                'message' => 'QR Code sudah kadaluarsa'
            ];
        }
        
        return [
            'valid' => true,
            'session' => $session,
            'message' => 'QR Code valid'
        ];
    }
    
    /**
     * Create new QR session untuk absensi
     */
    public function createSession(string $waktuMulai, string $waktuSelesai): ?array
    {
        $user = $this->session->get('user');
        
        // Generate unique session code
        $sessionCode = bin2hex(random_bytes(16)) . '_' . date('Ymd');
        
        // Data untuk QR
        $qrData = json_encode([
            'session_code' => $sessionCode,
            'tanggal' => date('Y-m-d'),
            'type' => 'attendance'
        ]);
        
        // Generate QR image
        $qrFilename = 'session_' . $sessionCode . '.png';
        $generated = $this->generate($qrData, $qrFilename);
        
        if (!$generated) {
            return null;
        }
        
        // Simpan ke database
        $sessionId = $this->qrSessionModel->create([
            'session_code' => $sessionCode,
            'tanggal' => date('Y-m-d'),
            'waktu_mulai' => $waktuMulai,
            'waktu_selesai' => $waktuSelesai,
            'is_active' => 1,
            'created_by' => $user['id']
        ]);
        
        if ($sessionId) {
            return $this->qrSessionModel->find($sessionId);
        }
        
        return null;
    }
    
    /**
     * Get active session
     */
    public function getActiveSession(): ?array
    {
        return $this->qrSessionModel->getActiveSession();
    }
    
    /**
     * Deactivate session
     */
    public function deactivateSession(int $id): bool
    {
        return $this->qrSessionModel->deactivate($id);
    }
    
    /**
     * Get today's sessions
     */
    public function getTodaySessions(): array
    {
        return $this->qrSessionModel->getTodaySessions();
    }
    
    /**
     * Generate QR untuk siswa (identifikasi personal)
     */
    public function generateSiswaQR(int $siswaId, string $nis): string
    {
        $qrData = json_encode([
            'type' => 'student_id',
            'siswa_id' => $siswaId,
            'nis' => $nis
        ]);

        $filename = 'siswa_' . $nis . '.png';
        $this->generate($qrData, $filename);

        return $filename;
    }
}
