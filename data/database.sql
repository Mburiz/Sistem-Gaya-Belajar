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

-- Clear existing data
TRUNCATE TABLE soal_tes;

-- Insert updated questions matching tes.php
INSERT INTO soal_tes (pertanyaan, tipe) VALUES
('Ketika belajar hal baru, apa yang kamu lakukan terlebih dahulu?', 'Visual'),
('Saat membaca buku, apa yang paling membantu kamu memahami isi bacaan?', 'Visual'),
('Dalam presentasi, bagian mana yang paling kamu sukai?', 'Visual'),
('Saat mengikuti pelatihan atau workshop, apa yang membuatmu paling mudah memahami materi?', 'Visual'),
('Ketika mengingat sesuatu, kamu lebih mudah mengingat lewat apa?', 'Visual'),
('Dalam belajar menggunakan aplikasi baru, kamu lebih suka?', 'Visual'),
('Ketika berdiskusi dengan teman, kamu lebih nyaman?', 'Visual'),
('Saat mengingat arah atau lokasi, kamu biasanya?', 'Visual');
INSERT INTO soal_tes (pertanyaan, tipe) VALUES
('Ketika belajar, saya lebih suka menggunakan alat bantu visual seperti peta pikiran atau diagram.', 'Visual');
-- Modify hasil_tes table to ensure it can handle 8 questions
ALTER TABLE hasil_tes 
MODIFY skor_visual INT DEFAULT 0,
MODIFY skor_auditori INT DEFAULT 0,
MODIFY skor_kinestetik INT DEFAULT 0;