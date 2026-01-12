<div class="profile-page">
    <div class="page-header">
        <h1><i class="fas fa-user-circle"></i> Profil Saya</h1>
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
                    <h2><?= e($profile['nama_lengkap'] ?? $user['username']) ?></h2>
                    <p class="email"><?= e($user['email']) ?></p>
                    <span class="badge <?= $user['role'] === 'admin' ? 'badge-primary' : 'badge-secondary' ?>">
                        <?= ucfirst($user['role']) ?>
                    </span>
                </div>
            </div>

            <?php if ($profile): ?>
            <div class="profile-details">
                <div class="detail-group">
                    <label><i class="fas fa-id-card"></i> NIS</label>
                    <span><?= e($profile['nis']) ?></span>
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

            <!-- Personal QR Code -->
            <div class="profile-qr">
                <h3><i class="fas fa-qrcode"></i> QR Code Personal</h3>
                <?php if ($profile['qr_code']): ?>
                <div class="qr-image">
                    <img src="<?= asset('uploads/qrcodes/' . $profile['qr_code']) ?>" alt="QR Code">
                </div>
                <p class="qr-info">Gunakan QR ini untuk identifikasi</p>
                <?php else: ?>
                <div class="qr-generate">
                    <p>Anda belum memiliki QR Code personal</p>
                    <button id="generateQR" class="btn btn-primary">
                        <i class="fas fa-qrcode"></i> Generate QR Code
                    </button>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <div class="profile-actions">
                <a href="<?= url('/profile/edit') ?>" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit Profil
                </a>
                <a href="<?= url('/dashboard') ?>" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('generateQR')?.addEventListener('click', function() {
    this.disabled = true;
    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generating...';
    
    fetch('<?= url('/profile/generate-qr') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message);
            this.disabled = false;
            this.innerHTML = '<i class="fas fa-qrcode"></i> Generate QR Code';
        }
    })
    .catch(error => {
        alert('Terjadi kesalahan');
        this.disabled = false;
        this.innerHTML = '<i class="fas fa-qrcode"></i> Generate QR Code';
    });
});
</script>
