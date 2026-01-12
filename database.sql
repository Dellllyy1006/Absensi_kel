-- =============================================
-- Absensi SMK Database Schema
-- =============================================

-- Create Database
CREATE DATABASE IF NOT EXISTS absensi_kel;
USE absensi_kel;

-- =============================================
-- Table: users
-- =============================================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'siswa') DEFAULT 'siswa',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_email (email),
    INDEX idx_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: siswa_profiles
-- =============================================
CREATE TABLE IF NOT EXISTS siswa_profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    nis VARCHAR(20) UNIQUE NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    kelas VARCHAR(20) NOT NULL,
    jurusan VARCHAR(50) NOT NULL,
    jenis_kelamin ENUM('L', 'P') NOT NULL,
    alamat TEXT,
    no_telepon VARCHAR(15),
    foto VARCHAR(255),
    qr_code VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_nis (nis),
    INDEX idx_kelas (kelas),
    INDEX idx_jurusan (jurusan)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: absensi
-- =============================================
CREATE TABLE IF NOT EXISTS absensi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    siswa_id INT NOT NULL,
    tanggal DATE NOT NULL,
    waktu_masuk TIME,
    waktu_keluar TIME,
    status ENUM('hadir', 'izin', 'sakit', 'alpha') DEFAULT 'hadir',
    keterangan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (siswa_id) REFERENCES siswa_profiles(id) ON DELETE CASCADE,
    UNIQUE KEY unique_absensi (siswa_id, tanggal),
    INDEX idx_tanggal (tanggal),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table: qr_sessions
-- =============================================
CREATE TABLE IF NOT EXISTS qr_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_code VARCHAR(100) UNIQUE NOT NULL,
    tanggal DATE NOT NULL,
    waktu_mulai TIME NOT NULL,
    waktu_selesai TIME NOT NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id),
    INDEX idx_session_code (session_code),
    INDEX idx_tanggal (tanggal),
    INDEX idx_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Insert Default Admin User
-- Password: admin123 (hashed)
-- =============================================
INSERT INTO users (username, email, password, role) VALUES 
('admin', 'admin@smk.sch.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- =============================================
-- Insert Sample Data (Optional - for testing)
-- =============================================

-- Sample Students
INSERT INTO users (username, email, password, role) VALUES 
('siswa1', 'siswa1@smk.sch.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'siswa'),
('siswa2', 'siswa2@smk.sch.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'siswa'),
('siswa3', 'siswa3@smk.sch.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'siswa');

-- Sample Profiles
INSERT INTO siswa_profiles (user_id, nis, nama_lengkap, kelas, jurusan, jenis_kelamin, alamat) VALUES 
(2, '20210001', 'Ahmad Fauzi', 'X-1', 'RPL', 'L', 'Jl. Merdeka No. 10'),
(3, '20210002', 'Siti Nurhaliza', 'X-1', 'RPL', 'P', 'Jl. Sudirman No. 22'),
(4, '20210003', 'Budi Santoso', 'X-2', 'TKJ', 'L', 'Jl. Pahlawan No. 5');

-- =============================================
-- Notes:
-- Default password for all users: password
-- You should change the admin password after first login
-- =============================================
