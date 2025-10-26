<?php
require_once 'helper/function.php';
cek_login();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tes Gaya Belajar</title>
  <link rel="stylesheet" href="asset/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
  <?php include 'layout/sidebar.php'; ?>
  <?php include 'layout/header.php'; ?>

  <div class="main-content">
    <div class="welcome-section">
      <h1>Tes Gaya Belajar</h1>
      <p class="welcome-text">
        Jawab beberapa pertanyaan berikut untuk mengetahui gaya belajar dominan kamu!
      </p>
    </div>

    <div class="test-container">
      <form action="hasil_tes.php" method="POST">
        <!-- Pertanyaan 1 -->
        <div class="question-card">
          <h3>1. Ketika belajar hal baru, apa yang kamu lakukan terlebih dahulu?</h3>
          <label><input type="radio" name="q1" value="visual" required> Melihat gambar atau diagram</label>
          <label><input type="radio" name="q1" value="auditori"> Mendengarkan penjelasan orang lain</label>
          <label><input type="radio" name="q1" value="kinestetik"> Langsung mencoba melakukan</label>
        </div>

        <!-- Pertanyaan 2 -->
        <div class="question-card">
          <h3>2. Saat membaca buku, apa yang paling membantu kamu memahami isi bacaan?</h3>
          <label><input type="radio" name="q2" value="visual" required> Gambar, warna, atau diagram di buku</label>
          <label><input type="radio" name="q2" value="auditori"> Membaca keras-keras atau mendengarkan audio</label>
          <label><input type="radio" name="q2" value="kinestetik"> Membuat catatan atau praktik langsung</label>
        </div>

        <!-- Pertanyaan 3 -->
        <div class="question-card">
          <h3>3. Dalam presentasi, bagian mana yang paling kamu sukai?</h3>
          <label><input type="radio" name="q3" value="visual" required> Slide dengan gambar dan warna menarik</label>
          <label><input type="radio" name="q3" value="auditori"> Penjelasan dari pembicara</label>
          <label><input type="radio" name="q3" value="kinestetik"> Aktivitas interaktif atau praktik</label>
        </div>

        <!-- Pertanyaan 4 -->
        <div class="question-card">
          <h3>4. Saat mengikuti pelatihan atau workshop, apa yang membuatmu paling mudah memahami materi?</h3>
          <label><input type="radio" name="q4" value="visual" required> Melihat demonstrasi atau video</label>
          <label><input type="radio" name="q4" value="auditori"> Mendengarkan penjelasan pembicara</label>
          <label><input type="radio" name="q4" value="kinestetik"> Langsung mencoba latihan atau simulasi</label>
        </div>

        <!-- Pertanyaan 5 -->
        <div class="question-card">
          <h3>5. Ketika mengingat sesuatu, kamu lebih mudah mengingat lewat...?</h3>
          <label><input type="radio" name="q5" value="visual" required> Gambar atau tulisan yang kamu lihat</label>
          <label><input type="radio" name="q5" value="auditori"> Suara atau kata yang kamu dengar</label>
          <label><input type="radio" name="q5" value="kinestetik"> Gerakan atau tindakan yang kamu lakukan</label>
        </div>

        <!-- Pertanyaan 6 -->
        <div class="question-card">
          <h3>6. Dalam belajar menggunakan aplikasi baru, kamu lebih suka...</h3>
          <label><input type="radio" name="q6" value="visual" required> Melihat tutorial video atau panduan bergambar</label>
          <label><input type="radio" name="q6" value="auditori"> Mendengarkan seseorang menjelaskan langkah-langkahnya</label>
          <label><input type="radio" name="q6" value="kinestetik"> Langsung mengklik dan mencoba sendiri</label>
        </div>

        <!-- Pertanyaan 7 -->
        <div class="question-card">
          <h3>7. Ketika berdiskusi dengan teman, kamu lebih nyaman...</h3>
          <label><input type="radio" name="q7" value="visual" required> Menggunakan papan tulis atau coretan skema</label>
          <label><input type="radio" name="q7" value="auditori"> Berbicara dan mendengarkan pendapat teman</label>
          <label><input type="radio" name="q7" value="kinestetik"> Menunjukkan atau mempraktikkan ide langsung</label>
        </div>

        <!-- Pertanyaan 8 -->
        <div class="question-card">
          <h3>8. Saat mengingat arah atau lokasi, kamu biasanya...</h3>
          <label><input type="radio" name="q8" value="visual" required> Mengingat bentuk jalan atau peta</label>
          <label><input type="radio" name="q8" value="auditori"> Mengingat petunjuk verbal seperti "belok kiri di lampu merah"</label>
          <label><input type="radio" name="q8" value="kinestetik"> Mengingat gerakan atau rute saat berjalan</label>
        </div>
        <div class="submit-section">
          <button type="submit" class="btn-submit">
            <i class="fa-solid fa-paper-plane"></i> Kirim Jawaban
          </button>
        </div>
      </form>
    </div>
  </div>

  <script src="asset/js/script.js"></script>
</body>
</html>
