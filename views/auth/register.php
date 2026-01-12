<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
</head>
<body class="auth-page">
    <div class="auth-container register-container">
        <div class="auth-card register-card">
            <div class="auth-header">
                <div class="auth-logo">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h1>Registrasi Siswa</h1>
                <p>Daftar untuk menggunakan sistem absensi</p>
            </div>

            <?php 
            $session = new \Core\Session();
            $flash = $session->getFlash();
            if ($flash): 
            ?>
            <div class="alert alert-<?= $flash['type'] ?>">
                <?= $flash['message'] ?>
            </div>
            <?php endif; ?>

            <form action="<?= url('/auth/register') ?>" method="POST" class="auth-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="username">
                            <i class="fas fa-user"></i>
                            Username
                        </label>
                        <input 
                            type="text" 
                            id="username" 
                            name="username" 
                            class="form-control"
                            placeholder="Username"
                            required
                            minlength="4"
                        >
                    </div>

                    <div class="form-group">
                        <label for="email">
                            <i class="fas fa-envelope"></i>
                            Email
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            class="form-control"
                            placeholder="email@example.com"
                            required
                        >
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="nis">
                            <i class="fas fa-id-card"></i>
                            NIS
                        </label>
                        <input 
                            type="text" 
                            id="nis" 
                            name="nis" 
                            class="form-control"
                            placeholder="Nomor Induk Siswa"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="nama_lengkap">
                            <i class="fas fa-user-tag"></i>
                            Nama Lengkap
                        </label>
                        <input 
                            type="text" 
                            id="nama_lengkap" 
                            name="nama_lengkap" 
                            class="form-control"
                            placeholder="Nama lengkap"
                            required
                        >
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="kelas">
                            <i class="fas fa-chalkboard"></i>
                            Kelas
                        </label>
                        <select id="kelas" name="kelas" class="form-control" required>
                            <option value="">Pilih Kelas</option>
                            <option value="X-1">X-1</option>
                            <option value="X-2">X-2</option>
                            <option value="XI-1">XI-1</option>
                            <option value="XI-2">XI-2</option>
                            <option value="XII-1">XII-1</option>
                            <option value="XII-2">XII-2</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="jurusan">
                            <i class="fas fa-graduation-cap"></i>
                            Jurusan
                        </label>
                        <select id="jurusan" name="jurusan" class="form-control" required>
                            <option value="">Pilih Jurusan</option>
                            <option value="RPL">Rekayasa Perangkat Lunak</option>
                            <option value="TKJ">Teknik Komputer Jaringan</option>
                            <option value="MM">Multimedia</option>
                            <option value="AKL">Akuntansi</option>
                            <option value="OTKP">Otomatisasi Tata Kelola Perkantoran</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>
                        <i class="fas fa-venus-mars"></i>
                        Jenis Kelamin
                    </label>
                    <div class="radio-group">
                        <label class="radio-label">
                            <input type="radio" name="jenis_kelamin" value="L" required>
                            <span>Laki-laki</span>
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="jenis_kelamin" value="P">
                            <span>Perempuan</span>
                        </label>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="password">
                            <i class="fas fa-lock"></i>
                            Password
                        </label>
                        <div class="password-input">
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                class="form-control"
                                placeholder="Min. 6 karakter"
                                required
                                minlength="6"
                            >
                            <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">
                            <i class="fas fa-lock"></i>
                            Konfirmasi Password
                        </label>
                        <div class="password-input">
                            <input 
                                type="password" 
                                id="confirm_password" 
                                name="confirm_password" 
                                class="form-control"
                                placeholder="Ulangi password"
                                required
                            >
                            <button type="button" class="password-toggle" onclick="togglePassword('confirm_password')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-user-plus"></i>
                    Daftar
                </button>
            </form>

            <div class="auth-footer">
                <p>Sudah punya akun? <a href="<?= url('/auth/login') ?>">Login</a></p>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = input.nextElementSibling.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
