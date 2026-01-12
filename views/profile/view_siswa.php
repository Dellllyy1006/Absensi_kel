<div class="profile-page">
    <div class="page-header">
        <h1><i class="fas fa-user-graduate"></i> Profil Siswa</h1>
        <a href="<?= url('/absensi') ?>" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="profile-container">
        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-photo">
                    <?php if ($profile && $profile['foto']): ?>
                    <img src="<?= asset('uploads/photos/' . $profile['foto']) ?>" alt="Foto Profil">
                    <?php else: ?>
                    <div class="photo-placeholder">
                        <i class="fas fa-user"></i>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="profile-name">
                    <h2><?= e($profile['nama_lengkap']) ?></h2>
                    <p class="nis">NIS: <?= e($profile['nis']) ?></p>
                    <div>
                        <span class="badge badge-primary"><?= e($profile['kelas']) ?></span>
                        <span class="badge badge-secondary"><?= e($profile['jurusan']) ?></span>
                    </div>
                </div>
            </div>

            <div class="profile-details">
                <div class="detail-group">
                    <label><i class="fas fa-id-card"></i> NIS</label>
                    <span><?= e($profile['nis']) ?></span>
                </div>
                <div class="detail-group">
                    <label><i class="fas fa-user"></i> Nama Lengkap</label>
                    <span><?= e($profile['nama_lengkap']) ?></span>
                </div>
                <div class="detail-group">
                    <label><i class="fas fa-chalkboard"></i> Kelas</label>
                    <span><?= e($profile['kelas']) ?></span>
                </div>
                <div class="detail-group">
                    <label><i class="fas fa-graduation-cap"></i> Jurusan</label>
                    <span><?= e($profile['jurusan']) ?></span>
                </div>
                <div class="detail-group">
                    <label><i class="fas fa-venus-mars"></i> Jenis Kelamin</label>
                    <span><?= getGenderLabel($profile['jenis_kelamin']) ?></span>
                </div>
                <div class="detail-group">
                    <label><i class="fas fa-phone"></i> No. Telepon</label>
                    <span><?= e($profile['no_telepon'] ?? '-') ?></span>
                </div>
                <div class="detail-group full-width">
                    <label><i class="fas fa-map-marker-alt"></i> Alamat</label>
                    <span><?= e($profile['alamat'] ?? '-') ?></span>
                </div>
            </div>

            <div class="profile-actions">
                <a href="<?= url('/absensi/history?siswa_id=' . $profile['id']) ?>" class="btn btn-primary">
                    <i class="fas fa-history"></i> Lihat Riwayat Absensi
                </a>
            </div>
        </div>
    </div>
</div>
