# Aplikasi Absensi Siswa SMK

Sistem absensi digital berbasis web dengan teknologi QR Code untuk kemudahan absensi siswa SMK.

## Fitur

### Untuk Siswa
- ✅ Registrasi akun siswa baru
- ✅ Login dengan username/email
- ✅ Scan QR Code untuk absensi
- ✅ Lihat profil pribadi
- ✅ Lihat riwayat absensi
- ✅ Generate QR Code personal

### Untuk Admin
- ✅ Dashboard dengan statistik absensi
- ✅ Buat sesi QR Code untuk absensi
- ✅ Input absensi manual
- ✅ Absensi massal per kelas
- ✅ Lihat data absensi harian
- ✅ Lihat riwayat absensi per siswa

## Teknologi

- **Backend**: PHP Native
- **Database**: MySQL
- **Frontend**: HTML5, CSS3, JavaScript
- **Library**:
  - Font Awesome (icons)
  - Inter Font (typography)
  - html5-qrcode (QR scanner)
  - phpqrcode (QR generator)

## Arsitektur

Aplikasi ini dibangun dengan prinsip **Clean Code** dan **SOLID**:

```
absensi_kel/
├── config/          # Konfigurasi database dan aplikasi
├── core/            # Core classes (Database, Controller, Model, Session)
│   └── Interfaces/  # Interface definitions
├── app/
│   ├── Controllers/ # Request handlers
│   ├── Models/      # Data models
│   ├── Repositories/# Data access layer
│   └── Services/    # Business logic
├── views/           # View templates
├── public/          # Public assets (CSS, JS, uploads)
├── helpers/         # Utility functions
└── vendor/          # Third-party libraries
```

## Instalasi

### Prasyarat
- XAMPP (PHP 7.4+ dan MySQL 5.7+)
- Web browser modern

### Langkah Instalasi

1. **Copy folder ke htdocs**
   ```
   C:\xampp\htdocs\absensi_kel\
   ```

2. **Buat database**
   - Buka phpMyAdmin: http://localhost/phpmyadmin
   - Buat database baru: `absensi_smk`
   - Import file `database.sql`

   Atau jalankan via command line:
   ```bash
   mysql -u root < database.sql
   ```

3. **Konfigurasi database**
   Edit file `config/database.php` jika diperlukan:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'absensi_smk');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   ```

4. **Akses aplikasi**
   ```
   http://localhost/absensi_kel/public/
   ```

## Akun Default

### Admin
- **Username**: admin
- **Email**: admin@smk.sch.id
- **Password**: password

### Siswa (sample)
- **Username**: siswa1, siswa2, siswa3
- **Password**: password

> ⚠️ **Penting**: Ubah password default setelah login pertama!

## Cara Penggunaan

### Sebagai Admin

1. **Login** dengan akun admin
2. **Buat Sesi QR** melalui menu "Sesi QR Code"
   - Tentukan waktu mulai dan selesai
   - Klik "Generate QR"
3. **Tampilkan QR Code** ke layar/proyektor
4. **Siswa scan** QR Code untuk absensi
5. **Lihat data** melalui menu "Data Absensi"

### Sebagai Siswa

1. **Registrasi** akun baru (atau gunakan akun sample)
2. **Login** ke sistem
3. **Scan QR Code** yang ditampilkan admin
4. **Lihat riwayat** absensi di dashboard atau menu "Riwayat"

## Screenshot

### Halaman Login
Modern login page dengan ilustrasi

### Dashboard Admin
Dashboard dengan statistik dan quick actions

### Dashboard Siswa
Profile summary dan riwayat absensi

### Scan QR Code
Camera scanner untuk absensi

## Pengembangan

### Struktur SOLID

- **Single Responsibility**: Setiap class memiliki satu tanggung jawab
- **Open/Closed**: Mudah diperluas tanpa modifikasi
- **Liskov Substitution**: Interface yang konsisten
- **Interface Segregation**: Interface yang spesifik
- **Dependency Inversion**: Bergantung pada abstraksi

### Menambah Fitur

1. Buat interface di `core/Interfaces/`
2. Buat model di `app/Models/`
3. Buat repository di `app/Repositories/`
4. Buat service di `app/Services/`
5. Buat controller di `app/Controllers/`
6. Tambah route di `public/index.php`
7. Buat view di `views/`

## License

MIT License

## Kontributor

Dibuat dengan ❤️ untuk SMK Indonesia
