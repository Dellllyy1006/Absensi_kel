<div class="edit-absensi-page">
    <div class="page-header">
        <h1><i class="fas fa-edit"></i> Edit Absensi</h1>
        <a href="<?= url('/absensi') ?>" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="<?= url('/absensi/update?id=' . $attendance['id']) ?>" method="POST">
                <?= csrfField() ?>
                
                <div class="student-info mb-4">
                    <h3><?= e($attendance['nama_lengkap']) ?></h3>
                    <p class="text-muted">
                        NIS: <?= e($attendance['nis']) ?> | 
                        Kelas: <?= e($attendance['kelas']) ?>
                    </p>
                </div>

                <div class="form-group">
                    <label for="tanggal">Tanggal</label>
                    <input type="date" id="tanggal" name="tanggal" class="form-control" 
                           value="<?= e($attendance['tanggal']) ?>" required>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="waktu_masuk">Waktu Masuk</label>
                            <input type="time" id="waktu_masuk" name="waktu_masuk" class="form-control" 
                                   value="<?= e($attendance['waktu_masuk']) ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="waktu_keluar">Waktu Keluar</label>
                            <input type="time" id="waktu_keluar" name="waktu_keluar" class="form-control" 
                                   value="<?= e($attendance['waktu_keluar']) ?>">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="status">Status Kehadiran</label>
                    <select id="status" name="status" class="form-control" required>
                        <option value="hadir" <?= $attendance['status'] === 'hadir' ? 'selected' : '' ?>>Hadir</option>
                        <option value="izin" <?= $attendance['status'] === 'izin' ? 'selected' : '' ?>>Izin</option>
                        <option value="sakit" <?= $attendance['status'] === 'sakit' ? 'selected' : '' ?>>Sakit</option>
                        <option value="alpha" <?= $attendance['status'] === 'alpha' ? 'selected' : '' ?>>Alpha</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="keterangan">Keterangan</label>
                    <textarea id="keterangan" name="keterangan" class="form-control" rows="3"><?= e($attendance['keterangan']) ?></textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
