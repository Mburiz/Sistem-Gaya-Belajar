CREATE DATABASE IF NOT EXISTS gaya_belajar;
USE gaya_belajar;

-- ==========================
-- 1️⃣ TABEL USER
-- ==========================
CREATE TABLE IF NOT EXISTS user (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  foto VARCHAR(255) DEFAULT 'default.png',
  tema ENUM('terang','gelap') DEFAULT 'terang',
  notif TINYINT(1) DEFAULT 0
);

-- ==========================
-- 2️⃣ TABEL HASIL TES
-- ==========================
CREATE TABLE IF NOT EXISTS hasil_tes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  hasil VARCHAR(50),
  skor_visual INT DEFAULT 0,
  skor_auditori INT DEFAULT 0,
  skor_kinestetik INT DEFAULT 0,
  tanggal TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE
);

-- ==========================
-- 3️⃣ TABEL SOAL TES
-- ==========================
CREATE TABLE IF NOT EXISTS soal_tes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  pertanyaan TEXT NOT NULL,
  tipe VARCHAR(50) NOT NULL
);

-- ==========================
-- 4️⃣ TABEL RIWAYAT LOGIN
-- ==========================
CREATE TABLE IF NOT EXISTS riwayat_login (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  waktu_login DATETIME DEFAULT CURRENT_TIMESTAMP,
  ip_address VARCHAR(50),
  FOREIGN KEY (user_id) REFERENCES user(id) ON DELETE CASCADE
);

-- ==========================
-- 5️⃣ DATA AWAL
-- ==========================
INSERT INTO user (nama, email, password, tema) VALUES
('Admin Sistem', 'admin@gayabelajar.com', '$2y$10$abcdefghijklmnopqrstuv', 'terang'),
('User Demo', 'user@gayabelajar.com', '$2y$10$abcdefghijklmnopqrstuv', 'gelap');

INSERT INTO soal_tes (pertanyaan, tipe) VALUES
('Saya lebih mudah memahami informasi jika melihat gambar, diagram, atau grafik.', 'Visual'),
('Saya lebih cepat mengerti pelajaran jika mendengarkan penjelasan guru.', 'Auditori'),
('Saya suka belajar sambil praktik langsung atau melakukan kegiatan.', 'Kinestetik'),
('Saya suka mencatat dengan warna-warna untuk membedakan materi.', 'Visual'),
('Saya suka mendengarkan musik saat belajar.', 'Auditori'),
('Saya suka bergerak saat sedang berpikir atau belajar.', 'Kinestetik');
