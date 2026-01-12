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
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <div class="auth-logo">
                    <i class="fas fa-qrcode"></i>
                </div>
                <h1><?= APP_NAME ?></h1>
                <p>Sistem Absensi Siswa SMK</p>
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

            <form action="<?= url('/auth/login') ?>" method="POST" class="auth-form">
                <div class="form-group">
                    <label for="identifier">
                        <i class="fas fa-user"></i>
                        Username atau Email
                    </label>
                    <input 
                        type="text" 
                        id="identifier" 
                        name="identifier" 
                        class="form-control"
                        placeholder="Masukkan username atau email"
                        required
                        autofocus
                    >
                </div>

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
                            placeholder="Masukkan password"
                            required
                        >
                        <button type="button" class="password-toggle" onclick="togglePassword('password')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-sign-in-alt"></i>
                    Login
                </button>
            </form>

            <div class="auth-footer">
                <p>Belum punya akun? <a href="<?= url('/auth/register') ?>">Daftar Sekarang</a></p>
            </div>
        </div>

        <div class="auth-illustration">
            <div class="illustration-content">
                <i class="fas fa-school"></i>
                <h2>Selamat Datang</h2>
                <p>Sistem absensi digital dengan teknologi QR Code untuk kemudahan absensi siswa.</p>
                <ul class="feature-list">
                    <li><i class="fas fa-check-circle"></i> Absensi dengan scan QR Code</li>
                    <li><i class="fas fa-check-circle"></i> Lihat riwayat absensi</li>
                    <li><i class="fas fa-check-circle"></i> Kelola profil siswa</li>
                </ul>
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
