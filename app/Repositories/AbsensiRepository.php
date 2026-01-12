<?php
/**
 * Absensi Repository
 * 
 * Repository untuk operasi data absensi
 */

namespace App\Repositories;

use App\Models\Absensi;
use Core\Interfaces\RepositoryInterface;

class AbsensiRepository implements RepositoryInterface
{
    private Absensi $model;

    public function __construct()
    {
        $this->model = new Absensi();
    }

    /**
     * @inheritDoc
     */
    public function find(int $id): ?array
    {
        return $this->model->find($id);
    }

    /**
     * @inheritDoc
     */
    public function findAll(array $conditions = []): array
    {
        return $this->model->findAll($conditions);
    }

    /**
     * @inheritDoc
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * @inheritDoc
     */
    public function update(int $id, array $data): bool
    {
        return $this->model->update($id, $data);
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id): bool
    {
        return $this->model->delete($id);
    }

    /**
     * Find by siswa and date
     * 
     * @param int $siswaId
     * @param string $tanggal
     * @return array|null
     */
    public function findBySiswaAndDate(int $siswaId, string $tanggal): ?array
    {
        return $this->model->findBySiswaAndDate($siswaId, $tanggal);
    }

    /**
     * Get absensi by date
     * 
     * @param string $tanggal
     * @return array
     */
    public function findByDate(string $tanggal): array
    {
        return $this->model->findByDate($tanggal);
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
        return $this->model->findBySiswa($siswaId, $limit);
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
        return $this->model->getMonthlyReport($siswaId, $month, $year);
    }

    /**
     * Get attendance statistics
     * 
     * @param int $siswaId
     * @param int $month
     * @param int $year
     * @return array
     */
    public function getStatistics(int $siswaId, int $month, int $year): array
    {
        return $this->model->getStatistics($siswaId, $month, $year);
    }

    /**
     * Get today's attendance
     * 
     * @return array
     */
    public function getTodayAttendance(): array
    {
        return $this->model->getTodayAttendance();
    }

    /**
     * Check if already checked in today
     * 
     * @param int $siswaId
     * @return bool
     */
    public function hasCheckedInToday(int $siswaId): bool
    {
        return $this->model->hasCheckedInToday($siswaId);
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
        return $this->model->checkIn($siswaId, $status, $keterangan);
    }

    /**
     * Record check out
     * 
     * @param int $siswaId
     * @return bool
     */
    public function checkOut(int $siswaId): bool
    {
        return $this->model->checkOut($siswaId);
    }

    /**
     * Get daily summary
     * 
     * @param string $tanggal
     * @return array
     */
    public function getDailySummary(string $tanggal): array
    {
        return $this->model->getDailySummary($tanggal);
    }

    /**
     * Find attendance detail with student info
     * 
     * @param int $id
     * @return array|null
     */
    public function findDetail(int $id): ?array
    {
        return $this->model->findDetail($id);
    }
}
