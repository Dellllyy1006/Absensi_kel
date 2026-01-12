<div class="history-page">
    <div class="page-header">
        <h1><i class="fas fa-history"></i> Riwayat Absensi</h1>
        <?php if (isset($isAdmin) && $isAdmin): ?>
        <p>Riwayat absensi: <?= e($profile['nama_lengkap']) ?> (<?= e($profile['nis']) ?>)</p>
        <?php endif; ?>
    </div>

    <!-- Month Filter -->
    <div class="filter-card">
        <form action="" method="GET" class="filter-form">
            <?php if (isset($isAdmin) && $isAdmin): ?>
            <input type="hidden" name="siswa_id" value="<?= $profile['id'] ?>">
            <?php endif; ?>
            <div class="filter-group">
                <label for="month">Bulan</label>
                <select id="month" name="month" class="form-control">
                    <?php for ($m = 1; $m <= 12; $m++): ?>
                    <option value="<?= $m ?>" <?= $selectedMonth == $m ? 'selected' : '' ?>>
                        <?= getMonthName($m) ?>
                    </option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="filter-group">
                <label for="year">Tahun</label>
                <select id="year" name="year" class="form-control">
                    <?php for ($y = date('Y'); $y >= date('Y') - 5; $y--): ?>
                    <option value="<?= $y ?>" <?= $selectedYear == $y ? 'selected' : '' ?>>
                        <?= $y ?>
                    </option>
                    <?php endfor; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-filter"></i> Filter
            </button>
        </form>
    </div>

    <!-- Statistics -->
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

    <!-- Attendance Table -->
    <div class="card">
        <div class="card-header">
            <h2>
                <i class="fas fa-calendar"></i>
                Absensi <?= getMonthName($history['month']) ?> <?= $history['year'] ?>
            </h2>
        </div>
        <div class="card-body">
            <?php if (empty($history['report'])): ?>
            <div class="empty-state">
                <i class="fas fa-calendar-times"></i>
                <p>Tidak ada data absensi untuk periode ini</p>
            </div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Hari</th>
                            <th>Masuk</th>
                            <th>Keluar</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                        foreach ($history['report'] as $item): 
                            $dayOfWeek = $days[date('w', strtotime($item['tanggal']))];
                        ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= formatDate($item['tanggal'], 'd M Y') ?></td>
                            <td><?= $dayOfWeek ?></td>
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
            <?php endif; ?>
        </div>
    </div>

    <div class="page-actions">
        <?php if (isset($isAdmin) && $isAdmin): ?>
        <a href="<?= url('/absensi') ?>" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Kembali ke Data Absensi
        </a>
        <?php else: ?>
        <a href="<?= url('/dashboard') ?>" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
        </a>
        <?php endif; ?>
    </div>
</div>
