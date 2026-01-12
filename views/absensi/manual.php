<div class="manual-page">
    <div class="page-header">
        <h1><i class="fas fa-edit"></i> Input Absensi Manual</h1>
        <p>Catat absensi siswa secara manual</p>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="<?= url('/absensi/manual') ?>" method="POST" class="absensi-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="siswa_id">Pilih Siswa</label>
                        <select id="siswa_id" name="siswa_id" class="form-control" required>
                            <option value="">-- Pilih Siswa --</option>
                            <?php foreach ($siswaList as $siswa): ?>
                            <option value="<?= $siswa['id'] ?>">
                                <?= e($siswa['nis']) ?> - <?= e($siswa['nama_lengkap']) ?> (<?= e($siswa['kelas']) ?>)
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="status">Status Kehadiran</label>
                        <select id="status" name="status" class="form-control" required>
                            <option value="">-- Pilih Status --</option>
                            <option value="hadir">Hadir</option>
                            <option value="izin">Izin</option>
                            <option value="sakit">Sakit</option>
                            <option value="alpha">Alpha</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="keterangan">Keterangan (Opsional)</label>
                    <textarea id="keterangan" name="keterangan" class="form-control" rows="3"
                              placeholder="Tambahkan keterangan jika diperlukan..."></textarea>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Absensi
                    </button>
                    <a href="<?= url('/absensi') ?>" class="btn btn-outline">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Quick filter by class -->
    <div class="card">
        <div class="card-header">
            <h2><i class="fas fa-filter"></i> Filter Siswa Berdasarkan Kelas</h2>
        </div>
        <div class="card-body">
            <div class="class-buttons">
                <button onclick="filterSiswa('')" class="btn btn-outline btn-sm">Semua</button>
                <?php foreach ($kelasList as $kelas): ?>
                <button onclick="filterSiswa('<?= e($kelas) ?>')" class="btn btn-outline btn-sm">
                    <?= e($kelas) ?>
                </button>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<script>
const siswaData = <?= json_encode($siswaList) ?>;
const siswaSelect = document.getElementById('siswa_id');

function filterSiswa(kelas) {
    // Clear current options except first
    siswaSelect.innerHTML = '<option value="">-- Pilih Siswa --</option>';
    
    // Filter and add options
    siswaData.forEach(siswa => {
        if (kelas === '' || siswa.kelas === kelas) {
            const option = document.createElement('option');
            option.value = siswa.id;
            option.textContent = `${siswa.nis} - ${siswa.nama_lengkap} (${siswa.kelas})`;
            siswaSelect.appendChild(option);
        }
    });
}
</script>
