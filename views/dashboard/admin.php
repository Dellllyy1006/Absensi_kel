<div class="dashboard admin-dashboard">
    <div class="page-header">
        <h1><i class="fas fa-tachometer-alt"></i> Dashboard Admin</h1>
        <p>Selamat datang, <?= e($session->get('user')['username']) ?>!</p>
    </div>

    <!-- Summary Cards -->
    <div class="stats-grid">
        <div class="stat-card stat-primary">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-info">
                <h3><?= $summary['total_siswa'] ?></h3>
                <p>Total Siswa</p>
            </div>
        </div>

        <div class="stat-card stat-success">
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-info">
                <h3><?= $summary['hadir'] ?></h3>
                <p>Hadir Hari Ini</p>
            </div>
        </div>

        <div class="stat-card stat-warning">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-info">
                <h3><?= $summary['izin'] + $summary['sakit'] ?></h3>
                <p>Izin/Sakit</p>
            </div>
        </div>

        <div class="stat-card stat-danger">
            <div class="stat-icon">
                <i class="fas fa-times-circle"></i>
            </div>
            <div class="stat-info">
                <h3><?= $summary['belum_absen'] ?></h3>
                <p>Belum Absen</p>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
        <h2><i class="fas fa-bolt"></i> Aksi Cepat</h2>
        <div class="action-buttons">
            <a href="<?= url('/qr') ?>" class="action-btn action-primary">
                <i class="fas fa-qrcode"></i>
                <span>Buat Sesi QR</span>
            </a>
            <a href="<?= url('/absensi/manual') ?>" class="action-btn action-secondary">
                <i class="fas fa-edit"></i>
                <span>Input Manual</span>
            </a>
            <a href="<?= url('/absensi/bulk') ?>" class="action-btn action-info">
                <i class="fas fa-users"></i>
                <span>Absensi Massal</span>
            </a>
            <a href="<?= url('/absensi') ?>" class="action-btn action-success">
                <i class="fas fa-clipboard-list"></i>
                <span>Lihat Data</span>
            </a>
        </div>
    </div>

    <!-- Today's Attendance Table -->
    <div class="card">
        <div class="card-header">
            <h2><i class="fas fa-calendar-day"></i> Absensi Hari Ini - <?= formatDate(date('Y-m-d')) ?></h2>
        </div>
        <div class="card-body">
            <?php if (empty($todayAttendance)): ?>
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <p>Belum ada data absensi hari ini</p>
            </div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>NIS</th>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>Waktu Masuk</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_slice($todayAttendance, 0, 10) as $item): ?>
                        <tr>
                            <td><?= e($item['nis']) ?></td>
                            <td><?= e($item['nama_lengkap']) ?></td>
                            <td><?= e($item['kelas']) ?></td>
                            <td><?= $item['waktu_masuk'] ? formatTime($item['waktu_masuk']) : '-' ?></td>
                            <td>
                                <span class="badge <?= getStatusBadge($item['status']) ?>">
                                    <?= getStatusLabel($item['status']) ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php if (count($todayAttendance) > 10): ?>
            <div class="text-center mt-3">
                <a href="<?= url('/absensi') ?>" class="btn btn-outline">
                    Lihat Semua <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
