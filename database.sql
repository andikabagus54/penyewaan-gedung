-- Buat database baru
CREATE DATABASE IF NOT EXISTS sewa_mwcnu;
USE sewa_mwcnu;

-- Tabel users
CREATE TABLE IF NOT EXISTS users (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    no_telepon VARCHAR(15) DEFAULT NULL,
    alamat TEXT DEFAULT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel gedung
CREATE TABLE IF NOT EXISTS gedung (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    nama_gedung VARCHAR(100) NOT NULL,
    kapasitas INT(11) NOT NULL,
    harga_per_hari DECIMAL(10,2) NOT NULL,
    fasilitas TEXT NOT NULL,
    status ENUM('tersedia', 'tidak_tersedia') DEFAULT 'tersedia',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel pemesanan
CREATE TABLE IF NOT EXISTS pemesanan (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    kode_booking VARCHAR(20) NOT NULL UNIQUE,
    user_id INT(11) NOT NULL,
    gedung_id INT(11) NOT NULL,
    tanggal_mulai DATE NOT NULL,
    tanggal_selesai DATE NOT NULL,
    total_hari INT(11) NOT NULL,
    total_harga DECIMAL(10,2) NOT NULL,
    status ENUM('menunggu_pembayaran', 'pending', 'dikonfirmasi', 'selesai', 'dibatalkan') DEFAULT 'menunggu_pembayaran',
    bukti_pembayaran VARCHAR(255) DEFAULT NULL,
    catatan TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (gedung_id) REFERENCES gedung(id) ON DELETE CASCADE
);

-- Insert Admin (username: admin, password: admin123)
INSERT INTO users (username, password, nama_lengkap, email, role) 
VALUES ('admin', MD5('admin123'), 'Administrator MWCNU', 'admin@mwcnu.com', 'admin');

-- Insert Data Gedung
INSERT INTO gedung (nama_gedung, kapasitas, harga_per_hari, fasilitas) VALUES
('Gedung Utama MWCNU', 500, 5000000, 'AC, Sound System, LCD Proyektor, Meja Kursi, WiFi, Ruang Ganti, Parkir Luas'),
('Aula Serbaguna', 300, 3000000, 'AC, Sound System, LCD Proyektor, WiFi, Parkir'),
('Ruang Pertemuan', 100, 1500000, 'AC, LCD Proyektor, WiFi, Meja Rapat');

-- Cek hasil
SELECT * FROM users;
SELECT * FROM gedung;