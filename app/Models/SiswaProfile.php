<?php
/**
 * Siswa Profile Model
 * 
 * Model untuk tabel siswa_profiles
 */

namespace App\Models;

use Core\Model;

class SiswaProfile extends Model
{
    protected string $table = 'siswa_profiles';
    
    protected array $fillable = [
        'user_id',
        'nis',
        'nama_lengkap',
        'kelas',
        'jurusan',
        'jenis_kelamin',
        'alamat',
        'no_telepon',
        'foto',
        'qr_code'
    ];

    /**
     * Find profile by user ID
     * 
     * @param int $userId
     * @return array|null
     */
    public function findByUserId(int $userId): ?array
    {
        return $this->findBy('user_id', $userId);
    }

    /**
     * Find profile by NIS
     * 
     * @param string $nis
     * @return array|null
     */
    public function findByNIS(string $nis): ?array
    {
        return $this->findBy('nis', $nis);
    }

    /**
     * Check if NIS exists
     * 
     * @param string $nis
     * @return bool
     */
    public function nisExists(string $nis): bool
    {
        return $this->findByNIS($nis) !== null;
    }

    /**
     * Get all siswa with user data
     * 
     * @return array
     */
    public function findAllWithUser(): array
    {
        $sql = "SELECT sp.*, u.username, u.email 
                FROM {$this->table} sp 
                JOIN users u ON sp.user_id = u.id 
                ORDER BY sp.nama_lengkap ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Get siswa by kelas
     * 
     * @param string $kelas
     * @return array
     */
    public function findByKelas(string $kelas): array
    {
        return $this->findAll(['kelas' => $kelas], 'nama_lengkap ASC');
    }

    /**
     * Get siswa by jurusan
     * 
     * @param string $jurusan
     * @return array
     */
    public function findByJurusan(string $jurusan): array
    {
        return $this->findAll(['jurusan' => $jurusan], 'nama_lengkap ASC');
    }

    /**
     * Get distinct kelas list
     * 
     * @return array
     */
    public function getKelasList(): array
    {
        $sql = "SELECT DISTINCT kelas FROM {$this->table} ORDER BY kelas ASC";
        $stmt = $this->db->query($sql);
        return array_column($stmt->fetchAll(), 'kelas');
    }

    /**
     * Get distinct jurusan list
     * 
     * @return array
     */
    public function getJurusanList(): array
    {
        $sql = "SELECT DISTINCT jurusan FROM {$this->table} ORDER BY jurusan ASC";
        $stmt = $this->db->query($sql);
        return array_column($stmt->fetchAll(), 'jurusan');
    }

    /**
     * Get profile with user info
     * 
     * @param int $userId
     * @return array|null
     */
    public function getFullProfile(int $userId): ?array
    {
        $sql = "SELECT sp.*, u.username, u.email, u.role, u.created_at as user_created_at
                FROM {$this->table} sp 
                JOIN users u ON sp.user_id = u.id 
                WHERE sp.user_id = ?";
        $stmt = $this->db->query($sql, [$userId]);
        $result = $stmt->fetch();
        return $result ?: null;
    }
}
