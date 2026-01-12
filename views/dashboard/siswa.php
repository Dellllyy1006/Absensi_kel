<div class="dashboard siswa-dashboard">
    <div class="page-header">
        <h1><i class="fas fa-home"></i> Dashboard Siswa</h1>
        <p>Selamat datang, <?= e($profile['nama_lengkap']) ?>!</p>
    </div>

    <!-- Profile Summary -->
    <div class="profile-summary-card">
        <div class="profile-avatar">
            <?php if ($profile['foto']): ?>
            <img src="<?= asset('uploads/photos/' . $profile['foto']) ?>" alt="Foto Profil">
            <?php else: ?>
            <div class="avatar-placeholder">
                <i class="fas fa-user"></i>
            </div>
            <?php endif; ?>
        </div>
        <div class="profile-info">
            <h2><?= e($profile['nama_lengkap']) ?></h2>
            <p class="nis">NIS: <?= e($profile['nis']) ?></p>
            <p class="kelas-jurusan">
                <span class="badge badge-primary"><?= e($profile['kelas']) ?></span>
                <span class="badge badge-secondary"><?= e($profile['jurusan']) ?></span>
            </p>
        </div>
        <div class="profile-actions">
            <a href="<?= url('/profile') ?>" class="btn btn-outline btn-sm">
                <i class="fas fa-user"></i> Profil
            </a>
            <a href="<?= url('/absensi/scan') ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-qrcode"></i> Scan Absensi
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid stats-4">
        <div class="stat-card stat-success">
            <div class="stat-icon"><i class="fas fa-check"></i></div>
            <div class="stat-info">
                <h3><?= $history['statistics']['hadir'] ?? 0 ?></h3>
                <p>Hadir</p>
            </div>
        </div>
        <div class="stat-card stat-info">
            <div class="stat-icon"><i class="fas fa-file-alt"></i></div>
            <div class="stat-info">
                <h3><?= $history['statistics']['izin'] ?? 0 ?></h3>
                <p>Izin</p>
            </div>
        </div>
        <div class="stat-card stat-warning">
            <div class="stat-icon"><i class="fas fa-medkit"></i></div>
            <div class="stat-info">
                <h3><?= $history['statistics']['sakit'] ?? 0 ?></h3>
                <p>Sakit</p>
            </div>
        </div>
        <div class="stat-card stat-danger">
            <div class="stat-icon"><i class="fas fa-times"></i></div>
            <div class="stat-info">
                <h3><?= $history['statistics']['alpha'] ?? 0 ?></h3>
                <p>Alpha</p>
            </div>
        </div>
    </div>

    <!-- Quick Actions for Siswa -->
    <div class="quick-actions">
        <h2><i class="fas fa-bolt"></i> Menu Cepat</h2>
        <div class="action-buttons action-buttons-center">
            <a href="<?= url('/absensi/scan') ?>" class="action-btn action-primary action-large">
                <i class="fas fa-qrcode"></i>
                <span>Scan QR Absensi</span>
                <small>Untuk absen masuk</small>
            </a>
            <a href="<?= url('/absensi/history') ?>" class="action-btn action-info action-large">
                <i class="fas fa-history"></i>
                <span>Riwayat Absensi</span>
                <small>Lihat record absensi</small>
            </a>
            <a href="<?= url('/profile') ?>" class="action-btn action-secondary action-large">
                <i class="fas fa-user-circle"></i>
                <span>Profil Saya</span>
                <small>Lihat dan edit profil</small>
            </a>
        </div>
    </div>

    <!-- Recent Attendance -->
    <div class="card">
        <div class="card-header">
            <h2>
                <i class="fas fa-calendar-alt"></i> 
                Absensi Bulan <?= getMonthName($history['month']) ?> <?= $history['year'] ?>
            </h2>
        </div>
        <div class="card-body">
            <?php if (empty($history['report'])): ?>
            <div class="empty-state">
                <i class="fas fa-calendar-times"></i>
                <p>Belum ada data absensi bulan ini</p>
            </div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Masuk</th>
                            <th>Keluar</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_slice($history['report'], -10) as $item): ?>
                        <tr>
                            <td><?= formatDate($item['tanggal'], 'd M Y') ?></td>
                            <td><?= $item['waktu_masuk'] ? formatTime($item['waktu_masuk']) : '-' ?></td>
                            <td><?= $item['waktu_keluar'] ? formatTime($item['waktu_keluar']) : '-' ?></td>
                            <td>
                                <span class="badge <?= getStatusBadge($item['status']) ?>">
                                    <?= getStatusLabel($item['status']) ?>
                                </span>
                            </td>
                            <td><?= e($item['keterangan'] ?? '-') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="text-center mt-3">
                <a href="<?= url('/absensi/history') ?>" class="btn btn-outline">
                    Lihat Semua Riwayat <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
