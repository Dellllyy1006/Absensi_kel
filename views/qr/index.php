<div class="qr-session-page">
    <div class="page-header">
        <h1><i class="fas fa-qrcode"></i> Sesi QR Absensi</h1>
        <p>Buat dan kelola sesi QR Code untuk absensi siswa</p>
    </div>

    <!-- Create New Session -->
    <div class="card">
        <div class="card-header">
            <h2><i class="fas fa-plus-circle"></i> Buat Sesi Baru</h2>
        </div>
        <div class="card-body">
            <form action="<?= url('/qr/create') ?>" method="POST" class="session-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="waktu_mulai">Waktu Mulai</label>
                        <input type="time" id="waktu_mulai" name="waktu_mulai" 
                               class="form-control" value="<?= date('H:i') ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="waktu_selesai">Waktu Selesai</label>
                        <input type="time" id="waktu_selesai" name="waktu_selesai" 
                               class="form-control" value="<?= date('H:i', strtotime('+30 minutes')) ?>" required>
                    </div>
                    <div class="form-group form-button">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-qrcode"></i> Generate QR
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Active Session -->
    <?php if ($activeSession): ?>
    <div class="card card-active">
        <div class="card-header">
            <h2><i class="fas fa-broadcast-tower"></i> Sesi Aktif</h2>
            <span class="badge badge-success pulse">AKTIF</span>
        </div>
        <div class="card-body">
            <div class="active-session">
                <div class="qr-preview">
                    <?php 
                    $qrPath = QRCODE_PATH . 'session_' . $activeSession['session_code'] . '.png';
                    $qrSrc = asset('uploads/qrcodes/session_' . $activeSession['session_code'] . '.png');
                    
                    if (file_exists($qrPath)) {
                        $qrData = base64_encode(file_get_contents($qrPath));
                        $qrSrc = 'data:image/png;base64,' . $qrData;
                    }
                    ?>
                    <img src="<?= $qrSrc ?>" alt="QR Code">
                </div>
                <div class="session-info">
                    <p><strong>Kode Sesi:</strong> <?= e($activeSession['session_code']) ?></p>
                    <p><strong>Waktu:</strong> <?= formatTime($activeSession['waktu_mulai']) ?> - <?= formatTime($activeSession['waktu_selesai']) ?></p>
                    <div class="session-actions">
                        <a href="<?= url('/qr/display?code=' . $activeSession['session_code']) ?>" 
                           class="btn btn-primary" target="_blank">
                            <i class="fas fa-expand"></i> Tampilkan Fullscreen
                        </a>
                        <a href="<?= url('/qr/deactivate?id=' . $activeSession['id']) ?>" 
                           class="btn btn-danger"
                           onclick="return confirm('Yakin ingin menonaktifkan sesi ini?')">
                            <i class="fas fa-stop"></i> Nonaktifkan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Today's Sessions -->
    <div class="card">
        <div class="card-header">
            <h2><i class="fas fa-list"></i> Sesi Hari Ini</h2>
        </div>
        <div class="card-body">
            <?php if (empty($sessions)): ?>
            <div class="empty-state">
                <i class="fas fa-calendar-times"></i>
                <p>Belum ada sesi QR hari ini</p>
            </div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Sesi</th>
                            <th>Waktu Mulai</th>
                            <th>Waktu Selesai</th>
                            <th>Dibuat Oleh</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($sessions as $item): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><code><?= e(substr($item['session_code'], 0, 16)) ?>...</code></td>
                            <td><?= formatTime($item['waktu_mulai']) ?></td>
                            <td><?= formatTime($item['waktu_selesai']) ?></td>
                            <td><?= e($item['created_by_name']) ?></td>
                            <td>
                                <?php if ($item['is_active']): ?>
                                <span class="badge badge-success">Aktif</span>
                                <?php else: ?>
                                <span class="badge badge-secondary">Nonaktif</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?= url('/qr/display?code=' . $item['session_code']) ?>" 
                                   class="btn btn-sm btn-outline" target="_blank" title="Lihat QR">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <?php if ($item['is_active']): ?>
                                <a href="<?= url('/qr/deactivate?id=' . $item['id']) ?>" 
                                   class="btn btn-sm btn-danger" title="Nonaktifkan"
                                   onclick="return confirm('Yakin ingin menonaktifkan?')">
                                    <i class="fas fa-stop"></i>
                                </a>
                                <?php endif; ?>
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
