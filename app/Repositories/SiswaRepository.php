<?php
/**
 * Siswa Repository
 * 
 * Repository untuk operasi data siswa
 */

namespace App\Repositories;

use App\Models\SiswaProfile;
use Core\Interfaces\RepositoryInterface;

class SiswaRepository implements RepositoryInterface
{
    private SiswaProfile $model;

    public function __construct()
    {
        $this->model = new SiswaProfile();
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
     * Find profile by user ID
     * 
     * @param int $userId
     * @return array|null
     */
    public function findByUserId(int $userId): ?array
    {
        return $this->model->findByUserId($userId);
    }

    /**
     * Find profile by NIS
     * 
     * @param string $nis
     * @return array|null
     */
    public function findByNIS(string $nis): ?array
    {
        return $this->model->findByNIS($nis);
    }

    /**
     * Check if NIS exists
     * 
     * @param string $nis
     * @return bool
     */
    public function nisExists(string $nis): bool
    {
        return $this->model->nisExists($nis);
    }

    /**
     * Get all siswa with user data
     * 
     * @return array
     */
    public function findAllWithUser(): array
    {
        return $this->model->findAllWithUser();
    }

    /**
     * Get siswa by kelas
     * 
     * @param string $kelas
     * @return array
     */
    public function findByKelas(string $kelas): array
    {
        return $this->model->findByKelas($kelas);
    }

    /**
     * Get siswa by jurusan
     * 
     * @param string $jurusan
     * @return array
     */
    public function findByJurusan(string $jurusan): array
    {
        return $this->model->findByJurusan($jurusan);
    }

    /**
     * Get distinct kelas list
     * 
     * @return array
     */
    public function getKelasList(): array
    {
        return $this->model->getKelasList();
    }

    /**
     * Get distinct jurusan list
     * 
     * @return array
     */
    public function getJurusanList(): array
    {
        return $this->model->getJurusanList();
    }

    /**
     * Get full profile with user info
     * 
     * @param int $userId
     * @return array|null
     */
    public function getFullProfile(int $userId): ?array
    {
        return $this->model->getFullProfile($userId);
    }

    /**
     * Count total siswa
     * 
     * @return int
     */
    public function countAll(): int
    {
        return $this->model->count();
    }
}
