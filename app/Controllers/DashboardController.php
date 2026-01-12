<?php
/**
 * Dashboard Controller
 * 
 * Handle dashboard views
 */

namespace App\Controllers;

use Core\Controller;
use App\Services\AbsensiService;
use App\Repositories\SiswaRepository;

class DashboardController extends Controller
{
    private AbsensiService $absensiService;
    private SiswaRepository $siswaRepository;

    public function __construct()
    {
        parent::__construct();
        $this->absensiService = new AbsensiService();
        $this->siswaRepository = new SiswaRepository();
    }

    /**
     * Show dashboard based on role
     */
    public function index(): void
    {
        $this->requireAuth();

        $user = $this->session->get('user');

        if ($user['role'] === 'admin') {
            $this->adminDashboard();
        } else {
            $this->siswaDashboard();
        }
    }

    /**
     * Admin dashboard
     */
    private function adminDashboard(): void
    {
        $summary = $this->absensiService->getDashboardSummary();
        $todayAttendance = $this->absensiService->getTodayAttendance();
        $kelasList = $this->siswaRepository->getKelasList();

        $this->render('dashboard/admin', [
            'title' => 'Dashboard Admin - ' . APP_NAME,
            'summary' => $summary,
            'todayAttendance' => $todayAttendance,
            'kelasList' => $kelasList
        ]);
    }

    /**
     * Siswa dashboard
     */
    private function siswaDashboard(): void
    {
        $user = $this->session->get('user');
        $profile = $user['profile'] ?? null;

        if (!$profile) {
            $this->setFlash('error', 'Profil siswa tidak ditemukan');
            $this->redirect('/auth/logout');
        }

        $month = (int) date('m');
        $year = (int) date('Y');
        
        $history = $this->absensiService->getSiswaHistory($profile['id'], $month, $year);

        $this->render('dashboard/siswa', [
            'title' => 'Dashboard - ' . APP_NAME,
            'profile' => $profile,
            'history' => $history
        ]);
    }
}
