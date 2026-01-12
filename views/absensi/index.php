<div class="absensi-page">
    <div class="page-header">
        <h1><i class="fas fa-clipboard-list"></i> Data Absensi</h1>
        <p>Kelola data absensi siswa</p>
    </div>

    <!-- Filter -->
    <div class="filter-card">
        <form action="" method="GET" class="filter-form">
            <div class="filter-group">
                <label for="tanggal">Tanggal</label>
                <input type="date" id="tanggal" name="tanggal" class="form-control" 
                       value="<?= e($selectedDate) ?>">
            </div>
            <div class="filter-group">
                <label for="kelas">Kelas</label>
                <select id="kelas" name="kelas" class="form-control">
                    <option value="">Semua Kelas</option>
                    <?php foreach ($kelasList as $k): ?>
                    <option value="<?= e($k) ?>" <?= $selectedKelas === $k ? 'selected' : '' ?>>
                        <?= e($k) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-filter"></i> Filter
            </button>
        </form>
    </div>

    <!-- Summary -->
    <div class="summary-cards">
        <div class="summary-item summary-success">
            <span class="count"><?= $summary['hadir'] ?? 0 ?></span>
            <span class="label">Hadir</span>
        </div>
        <div class="summary-item summary-info">
            <span class="count"><?= $summary['izin'] ?? 0 ?></span>
            <span class="label">Izin</span>
        </div>
        <div class="summary-item summary-warning">
            <span class="count"><?= $summary['sakit'] ?? 0 ?></span>
            <span class="label">Sakit</span>
        </div>
        <div class="summary-item summary-danger">
            <span class="count"><?= $summary['alpha'] ?? 0 ?></span>
            <span class="label">Alpha</span>
        </div>
    </div>

    <!-- Attendance Table -->
    <div class="card">
        <div class="card-header">
            <h2>Absensi Tanggal <?= formatDate($selectedDate) ?></h2>
        </div>
        <div class="card-body">
            <?php if (empty($attendance)): ?>
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <p>Tidak ada data absensi untuk tanggal ini</p>
            </div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIS</th>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>Jurusan</th>
                            <th>Masuk</th>
                            <th>Keluar</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($attendance as $item): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= e($item['nis']) ?></td>
                            <td><?= e($item['nama_lengkap']) ?></td>
                            <td><?= e($item['kelas']) ?></td>
                            <td><?= e($item['jurusan']) ?></td>
                            <td><?= $item['waktu_masuk'] ? formatTime($item['waktu_masuk']) : '-' ?></td>
                            <td><?= $item['waktu_keluar'] ? formatTime($item['waktu_keluar']) : '-' ?></td>
                            <td>
                                <span class="badge <?= getStatusBadge($item['status']) ?>">
                                    <?= getStatusLabel($item['status']) ?>
                                </span>
                            </td>
                            <td>
                                <a href="<?= url('/absensi/history?siswa_id=' . $item['siswa_id']) ?>" 
                                   class="btn btn-sm btn-outline" title="Riwayat">
                                    <i class="fas fa-history"></i>
                                </a>
                                <a href="<?= url('/absensi/edit?id=' . $item['id']) ?>" 
                                   class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?= url('/absensi/delete?id=' . $item['id']) ?>" 
                                   class="btn btn-sm btn-danger" title="Hapus"
                                   onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
