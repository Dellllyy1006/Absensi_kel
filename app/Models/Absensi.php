<?php
/**
 * Absensi Model
 * 
 * Model untuk tabel absensi
 */

namespace App\Models;

use Core\Model;

class Absensi extends Model
{
    protected string $table = 'absensi';
    
    protected array $fillable = [
        'siswa_id',
        'tanggal',
        'waktu_masuk',
        'waktu_keluar',
        'status',
        'keterangan'
    ];

    /**
     * Find absensi by siswa and date
     * 
     * @param int $siswaId
     * @param string $tanggal
     * @return array|null
     */
    public function findBySiswaAndDate(int $siswaId, string $tanggal): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE siswa_id = ? AND tanggal = ?";
        $stmt = $this->db->query($sql, [$siswaId, $tanggal]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Get absensi by date
     * 
     * @param string $tanggal
     * @return array
     */
    public function findByDate(string $tanggal): array
    {
        $sql = "SELECT a.*, sp.nis, sp.nama_lengkap, sp.kelas, sp.jurusan 
                FROM {$this->table} a 
                JOIN siswa_profiles sp ON a.siswa_id = sp.id 
                WHERE a.tanggal = ? 
                ORDER BY sp.nama_lengkap ASC";
        $stmt = $this->db->query($sql, [$tanggal]);
        return $stmt->fetchAll();
    }

    /**
     * Get absensi history by siswa
     * 
     * @param int $siswaId
     * @param int $limit
     * @return array
     */
    public function findBySiswa(int $siswaId, int $limit = 30): array
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE siswa_id = ? 
                ORDER BY tanggal DESC 
                LIMIT ?";
        $stmt = $this->db->query($sql, [$siswaId, $limit]);
        return $stmt->fetchAll();
    }

    /**
     * Get monthly report
     * 
     * @param int $siswaId
     * @param int $month
     * @param int $year
     * @return array
     */
    public function getMonthlyReport(int $siswaId, int $month, int $year): array
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE siswa_id = ? 
                AND MONTH(tanggal) = ? 
                AND YEAR(tanggal) = ? 
                ORDER BY tanggal ASC";
        $stmt = $this->db->query($sql, [$siswaId, $month, $year]);
        return $stmt->fetchAll();
    }

    /**
     * Get attendance statistics for siswa
     * 
     * @param int $siswaId
     * @param int $month
     * @param int $year
     * @return array
     */
    public function getStatistics(int $siswaId, int $month, int $year): array
    {
        $sql = "SELECT 
                    COUNT(CASE WHEN status = 'hadir' THEN 1 END) as hadir,
                    COUNT(CASE WHEN status = 'izin' THEN 1 END) as izin,
                    COUNT(CASE WHEN status = 'sakit' THEN 1 END) as sakit,
                    COUNT(CASE WHEN status = 'alpha' THEN 1 END) as alpha,
                    COUNT(*) as total
                FROM {$this->table} 
                WHERE siswa_id = ? 
                AND MONTH(tanggal) = ? 
                AND YEAR(tanggal) = ?";
        $stmt = $this->db->query($sql, [$siswaId, $month, $year]);
        return $stmt->fetch();
    }

    /**
     * Get all attendance for today with siswa info
     * 
     * @return array
     */
    public function getTodayAttendance(): array
    {
        return $this->findByDate(date('Y-m-d'));
    }

    /**
     * Check if already checked in today
     * 
     * @param int $siswaId
     * @return bool
     */
    public function hasCheckedInToday(int $siswaId): bool
    {
        $today = date('Y-m-d');
        return $this->findBySiswaAndDate($siswaId, $today) !== null;
    }

    /**
     * Record check in
     * 
     * @param int $siswaId
     * @param string $status
     * @param string|null $keterangan
     * @return int|false
     */
    public function checkIn(int $siswaId, string $status = 'hadir', ?string $keterangan = null)
    {
        return $this->create([
            'siswa_id' => $siswaId,
            'tanggal' => date('Y-m-d'),
            'waktu_masuk' => date('H:i:s'),
            'status' => $status,
            'keterangan' => $keterangan
        ]);
    }

    /**
     * Record check out
     * 
     * @param int $siswaId
     * @return bool
     */
    public function checkOut(int $siswaId): bool
    {
        $today = date('Y-m-d');
        $absensi = $this->findBySiswaAndDate($siswaId, $today);
        
        if ($absensi) {
            return $this->update($absensi['id'], [
                'waktu_keluar' => date('H:i:s')
            ]);
        }
        
        return false;
    }

    /**
     * Get daily summary
     * 
     * @param string $tanggal
     * @return array
     */
    public function getDailySummary(string $tanggal): array
    {
        $sql = "SELECT 
                    COUNT(CASE WHEN status = 'hadir' THEN 1 END) as hadir,
                    COUNT(CASE WHEN status = 'izin' THEN 1 END) as izin,
                    COUNT(CASE WHEN status = 'sakit' THEN 1 END) as sakit,
                    COUNT(CASE WHEN status = 'alpha' THEN 1 END) as alpha,
                    COUNT(*) as total
                FROM {$this->table} 
                WHERE tanggal = ?";
        $stmt = $this->db->query($sql, [$tanggal]);
        return $stmt->fetch();
    }

    /**
     * Find attendance detail with student info
     * 
     * @param int $id
     * @return array|null
     */
    public function findDetail(int $id): ?array
    {
        $sql = "SELECT a.*, sp.nis, sp.nama_lengkap, sp.kelas, sp.jurusan 
                FROM {$this->table} a 
                JOIN siswa_profiles sp ON a.siswa_id = sp.id 
                WHERE a.id = ?";
        $stmt = $this->db->query($sql, [$id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }
}
