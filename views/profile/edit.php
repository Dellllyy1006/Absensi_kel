<div class="profile-page">
    <div class="page-header">
        <h1><i class="fas fa-user-edit"></i> Edit Profil</h1>
    </div>

    <div class="profile-container">
        <div class="card">
            <div class="card-body">
                <form action="<?= url('/profile/update') ?>" method="POST" enctype="multipart/form-data">
                    
                    <?php if ($profile): ?>
                    <!-- Siswa Profile Edit -->
                    <div class="form-section">
                        <h3><i class="fas fa-user"></i> Informasi Pribadi</h3>
                        
                        <div class="form-group">
                            <label for="foto">Foto Profil</label>
                            <div class="photo-upload">
                                <div class="current-photo">
                                    <?php if ($profile['foto']): ?>
                                    <img src="<?= asset('uploads/photos/' . $profile['foto']) ?>" alt="Foto Profil">
                                    <?php else: ?>
                                    <div class="photo-placeholder-small">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <div class="upload-input">
                                    <input type="file" id="foto" name="foto" accept="image/*" class="form-control">
                                    <small>Format: JPG, PNG, GIF. Max: 2MB</small>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="nama_lengkap">Nama Lengkap</label>
                                <input type="text" id="nama_lengkap" name="nama_lengkap" 
                                       class="form-control" value="<?= e($profile['nama_lengkap']) ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" 
                                       class="form-control" value="<?= e($user['email']) ?>" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>NIS</label>
                                <input type="text" class="form-control" value="<?= e($profile['nis']) ?>" disabled>
                                <small>NIS tidak dapat diubah</small>
                            </div>
                            <div class="form-group">
                                <label>Kelas</label>
                                <input type="text" class="form-control" value="<?= e($profile['kelas']) ?>" disabled>
                                <small>Hubungi admin untuk mengubah kelas</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3><i class="fas fa-address-card"></i> Kontak</h3>
                        
                        <div class="form-group">
                            <label for="no_telepon">No. Telepon</label>
                            <input type="text" id="no_telepon" name="no_telepon" 
                                   class="form-control" value="<?= e($profile['no_telepon'] ?? '') ?>"
                                   placeholder="08xxxxxxxxxx">
                        </div>

                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <textarea id="alamat" name="alamat" class="form-control" rows="3"
                                      placeholder="Alamat lengkap"><?= e($profile['alamat'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <?php else: ?>
                    <!-- Admin Profile Edit -->
                    <div class="form-section">
                        <h3><i class="fas fa-user-cog"></i> Informasi Akun</h3>
                        
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" class="form-control" value="<?= e($user['username']) ?>" disabled>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" 
                                   class="form-control" value="<?= e($user['email']) ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="password">Password Baru (Kosongkan jika tidak ingin mengubah)</label>
                            <input type="password" id="password" name="password" 
                                   class="form-control" minlength="6"
                                   placeholder="Minimal 6 karakter">
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                        <a href="<?= url('/profile') ?>" class="btn btn-outline">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
