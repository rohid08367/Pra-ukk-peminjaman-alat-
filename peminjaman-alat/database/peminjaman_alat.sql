CREATE DATABASE peminjaman_alat;
USE peminjaman_alat;

-- ======================
-- TABEL USERS
-- ======================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    role ENUM('admin','petugas','user') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ======================
-- TABEL KATEGORI
-- ======================
CREATE TABLE kategori (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(100)
);

-- ======================
-- TABEL ALAT
-- ======================
CREATE TABLE alat (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_alat VARCHAR(100),
    kategori_id INT,
    stok INT,
    status ENUM('tersedia','dipinjam','rusak') DEFAULT 'tersedia',
    FOREIGN KEY (kategori_id) REFERENCES kategori(id)
);

-- ======================
-- TABEL PEMINJAMAN
-- ======================
CREATE TABLE peminjaman (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    alat_id INT,
    tanggal_pinjam DATE,
    tanggal_kembali DATE,
    status ENUM('pending','disetujui','ditolak','dikembalikan') DEFAULT 'pending',
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (alat_id) REFERENCES alat(id)
);

-- ======================
-- TABEL LOG AKTIVITAS
-- ======================
CREATE TABLE log_aktivitas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    aktivitas TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ======================
-- SEED ADMIN (WAJIB)
-- password: rohid123
-- ======================
INSERT INTO users (nama, email, password, role)
VALUES (
    'Admin Utama',
    'rohidarkan08@gmail.com',
    '$2y$10$5cRzR5K5YXhYzu3/n6PvUOQ9HfB6p8x5Q3z/7f1Hwr9z4mZzv3d5a',
    'admin'
);
