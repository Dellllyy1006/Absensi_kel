<div class="bulk-page">
    <div class="page-header">
        <h1><i class="fas fa-users"></i> Absensi Massal</h1>
        <p>Catat absensi untuk seluruh kelas sekaligus</p>
    </div>

    <!-- Class Selection -->
    <div class="filter-card">
        <form action="" method="GET" class="filter-form">
            <div class="filter-group">
                <label for="kelas">Pilih Kelas</label>
                <select id="kelas" name="kelas" class="form-control" required onchange="this.form.submit()">
                    <option value="">-- Pilih Kelas --</option>
                    <?php foreach ($kelasList as $kelas): ?>
                    <option value="<?= e($kelas) ?>" <?= $selectedKelas === $kelas ? 'selected' : '' ?>>
                        <?= e($kelas) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>
    </div>

    <?php if (!empty($siswaList)): ?>
    <!-- Bulk Attendance Form -->
    <div class="card">
        <div class="card-header">
            <h2>
                <i class="fas fa-clipboard-check"></i>
                Absensi Kelas <?= e($selectedKelas) ?> - <?= formatDate(date('Y-m-d')) ?>
            </h2>
            <div class="bulk-actions">
                <button type="button" class="btn btn-sm btn-success" onclick="setAllStatus('hadir')">
                    <i class="fas fa-check-double"></i> Semua Hadir
                </button>
                <button type="button" class="btn btn-sm btn-danger" onclick="setAllStatus('alpha')">
                    <i class="fas fa-times"></i> Semua Alpha
                </button>
            </div>
        </div>
        <div class="card-body">
            <form action="<?= url('/absensi/bulk') ?>" method="POST">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th width="50">No</th>
                                <th>NIS</th>
                                <th>Nama Lengkap</th>
                                <th width="100">Hadir</th>
                                <th width="100">Izin</th>
                                <th width="100">Sakit</th>
                                <th width="100">Alpha</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach ($siswaList as $siswa): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= e($siswa['nis']) ?></td>
                                <td><?= e($siswa['nama_lengkap']) ?></td>
                                <td class="text-center">
                                    <label class="radio-status">
                                        <input type="radio" name="attendance[<?= $siswa['id'] ?>]" value="hadir" checked>
                                        <span class="radio-mark hadir"></span>
                                    </label>
                                </td>
                                <td class="text-center">
                                    <label class="radio-status">
                                        <input type="radio" name="attendance[<?= $siswa['id'] ?>]" value="izin">
                                        <span class="radio-mark izin"></span>
                                    </label>
                                </td>
                                <td class="text-center">
                                    <label class="radio-status">
                                        <input type="radio" name="attendance[<?= $siswa['id'] ?>]" value="sakit">
                                        <span class="radio-mark sakit"></span>
                                    </label>
                                </td>
                                <td class="text-center">
                                    <label class="radio-status">
                                        <input type="radio" name="attendance[<?= $siswa['id'] ?>]" value="alpha">
                                        <span class="radio-mark alpha"></span>
                                    </label>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save"></i> Simpan Semua Absensi
                    </button>
                    <a href="<?= url('/absensi') ?>" class="btn btn-outline">
                        <i class="fas fa-arrow-left"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
    <?php else: ?>
    <div class="card">
        <div class="card-body">
            <div class="empty-state">
                <i class="fas fa-chalkboard-teacher"></i>
                <p>Pilih kelas untuk menampilkan daftar siswa</p>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
function setAllStatus(status) {
    const radios = document.querySelectorAll(`input[value="${status}"]`);
    radios.forEach(radio => {
        radio.checked = true;
    });
}
</script>
