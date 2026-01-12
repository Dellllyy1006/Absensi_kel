<?php
/**
 * Absensi Service
 * 
 * Service untuk business logic absensi
 * Mengikuti prinsip Single Responsibility (SRP)
 */

namespace App\Services;

use App\Repositories\AbsensiRepository;
use App\Repositories\SiswaRepository;
use App\Models\QRSession;

class AbsensiService
{
    private AbsensiRepository $absensiRepository;
    private SiswaRepository $siswaRepository;
    private QRSession $qrSession;

    public function __construct()
    {
        $this->absensiRepository = new AbsensiRepository();
        $this->siswaRepository = new SiswaRepository();
        $this->qrSession = new QRSession();
    }

    /**
     * Process QR attendance scan
     * 
     * @param int $siswaId
     * @param string $qrData
     * @return array
     */
    public function processQRScan(int $siswaId, string $qrData): array
    {
        // Decode QR data
        $decoded = json_decode($qrData, true);

        if (!$decoded || !isset($decoded['session_code'])) {
            return [
                'success' => false,
                'message' => 'QR Code tidak valid'
            ];
        }

        // Validate session
        if (!$this->qrSession->isValidSession($decoded['session_code'])) {
            return [
                'success' => false,
                'message' => 'Sesi absensi tidak valid atau sudah berakhir'
            ];
        }

        // Check if already checked in today
        if ($this->absensiRepository->hasCheckedInToday($siswaId)) {
            return [
                'success' => false,
                'message' => 'Anda sudah melakukan absensi hari ini'
            ];
        }

        // Record attendance
        $absensiId = $this->absensiRepository->checkIn($siswaId);

        if ($absensiId) {
            return [
                'success' => true,
                'message' => 'Absensi berhasil dicatat pada ' . date('H:i:s'),
                'absensi_id' => $absensiId
            ];
        }

        return [
            'success' => false,
            'message' => 'Gagal mencatat absensi'
        ];
    }

    /**
     * Manual attendance by admin
     * 
     * @param int $siswaId
     * @param string $status
     * @param string|null $keterangan
     * @return array
     */
    public function recordManualAttendance(int $siswaId, string $status, ?string $keterangan = null): array
    {
        // Check if already has attendance today
        $existing = $this->absensiRepository->findBySiswaAndDate($siswaId, date('Y-m-d'));

        if ($existing) {
            // Update existing
            $success = $this->absensiRepository->update($existing['id'], [
                'status' => $status,
                'keterangan' => $keterangan
            ]);

            return [
                'success' => $success,
                'message' => $success ? 'Absensi berhasil diperbarui' : 'Gagal memperbarui absensi'
            ];
        }

        // Create new
        $absensiId = $this->absensiRepository->create([
            'siswa_id' => $siswaId,
            'tanggal' => date('Y-m-d'),
            'waktu_masuk' => date('H:i:s'),
            'status' => $status,
            'keterangan' => $keterangan
        ]);

        return [
            'success' => $absensiId !== false,
            'message' => $absensiId ? 'Absensi berhasil dicatat' : 'Gagal mencatat absensi'
        ];
    }

    /**
     * Get attendance summary for dashboard
     * 
     * @return array
     */
    public function getDashboardSummary(): array
    {
        $today = date('Y-m-d');
        $dailySummary = $this->absensiRepository->getDailySummary($today);
        $totalSiswa = $this->siswaRepository->countAll();

        return [
            'tanggal' => $today,
            'total_siswa' => $totalSiswa,
            'hadir' => $dailySummary['hadir'] ?? 0,
            'izin' => $dailySummary['izin'] ?? 0,
            'sakit' => $dailySummary['sakit'] ?? 0,
            'alpha' => $dailySummary['alpha'] ?? 0,
            'belum_absen' => $totalSiswa - ($dailySummary['total'] ?? 0)
        ];
    }

    /**
     * Get siswa attendance history
     * 
     * @param int $siswaId
     * @param int $month
     * @param int $year
     * @return array
     */
    public function getSiswaHistory(int $siswaId, int $month = 0, int $year = 0): array
    {
        if ($month === 0) {
            $month = (int) date('m');
        }
        if ($year === 0) {
            $year = (int) date('Y');
        }

        $report = $this->absensiRepository->getMonthlyReport($siswaId, $month, $year);
        $statistics = $this->absensiRepository->getStatistics($siswaId, $month, $year);

        return [
            'month' => $month,
            'year' => $year,
            'report' => $report,
            'statistics' => $statistics
        ];
    }

    /**
     * Get today's attendance list
     * 
     * @return array
     */
    public function getTodayAttendance(): array
    {
        return $this->absensiRepository->getTodayAttendance();
    }

    /**
     * Get attendance by date
     * 
     * @param string $tanggal
     * @return array
     */
    public function getAttendanceByDate(string $tanggal): array
    {
        return $this->absensiRepository->findByDate($tanggal);
    }

    /**
     * Check out siswa
     * 
     * @param int $siswaId
     * @return array
     */
    public function checkOut(int $siswaId): array
    {
        $success = $this->absensiRepository->checkOut($siswaId);

        return [
            'success' => $success,
            'message' => $success ? 'Check-out berhasil pada ' . date('H:i:s') : 'Gagal check-out'
        ];
    }
}
