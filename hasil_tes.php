<?php
require_once 'helper/function.php';
// jangan paksa login di halaman hasil; biarkan guest melihat hasil
if (session_status() === PHP_SESSION_NONE) session_start();
$user_id = $_SESSION['user_id'] ?? null;

// pastikan ada data
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST)) {
  header("Location: tes.php");
  exit;
}

// Hitung total jawaban
$visual = $auditori = $kinestetik = 0;

foreach ($_POST as $jawaban) {
  switch ($jawaban) {
    case 'visual': $visual++; break;
    case 'auditori': $auditori++; break;
    case 'kinestetik': $kinestetik++; break;
  }
}

// Tentukan hasil tertinggi
if ($visual >= $auditori && $visual >= $kinestetik) {
  $hasil = 'Visual';
} elseif ($auditori >= $visual && $auditori >= $kinestetik) {
  $hasil = 'Auditori';
} else {
  $hasil = 'Kinestetik';
}

// Simpan ke database hanya jika user login
$saved = false;
$insert_error = null;
if ($user_id !== null) {
  // pastikan user_id valid (ada di tabel user) untuk menghindari foreign key error
  $check = mysqli_prepare($koneksi, "SELECT id FROM user WHERE id = ? LIMIT 1");
  $user_exists = false;
  if ($check) {
    mysqli_stmt_bind_param($check, "i", $user_id);
    mysqli_stmt_execute($check);
    mysqli_stmt_store_result($check);
    if (mysqli_stmt_num_rows($check) > 0) $user_exists = true;
    mysqli_stmt_close($check);
  } else {
    // jika prepare check gagal, coba query langsung
    $res_check = mysqli_query($koneksi, "SELECT id FROM user WHERE id = " . (int)$user_id . " LIMIT 1");
    if ($res_check && mysqli_num_rows($res_check) > 0) $user_exists = true;
  }

  if ($user_exists) {
    $stmt = mysqli_prepare($koneksi, "
      INSERT INTO hasil_tes (user_id, hasil, skor_visual, skor_auditori, skor_kinestetik)
      VALUES (?, ?, ?, ?, ?)
    ");
  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "isiii", $user_id, $hasil, $visual, $auditori, $kinestetik);
    mysqli_stmt_execute($stmt);
    // periksa apakah benar-benar masuk
    $last_id = mysqli_insert_id($koneksi);
    if ($last_id && $last_id > 0) {
      $saved = true;
    } else {
      $insert_error = mysqli_error($koneksi) ?: 'Tidak ada ID insert dikembalikan.';
    }
    mysqli_stmt_close($stmt);
  } else {
    $insert_error = 'User tidak ditemukan. Silakan login ulang sebelum menyimpan hasil.';
    error_log('Gagal insert: user_id tidak ditemukan (' . $user_id . ')');
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Hasil Tes Gaya Belajar</title>
  <link rel="stylesheet" href="asset/css/style.css">
</head>
<body>
  <?php include 'layout/sidebar.php'; ?>
  <?php include 'layout/header.php'; ?>

  <div class="main-content">
    <h2>Hasil Tes Gaya Belajar Kamu</h2>

    <div class="card" style="max-width:600px;">
      <h3>Kamu termasuk tipe <strong><?= $hasil ?></strong></h3>

      <p>
        <?php if ($hasil === 'Visual'): ?>
          Kamu lebih mudah belajar dengan melihat gambar, warna, atau diagram.
        <?php elseif ($hasil === 'Auditori'): ?>
          Kamu lebih cepat memahami pelajaran dengan mendengarkan penjelasan atau berdiskusi.
        <?php else: ?>
          Kamu belajar terbaik dengan praktik langsung dan gerakan.
        <?php endif; ?>
      </p>

      <hr>

      <?php
      $total = $visual + $auditori + $kinestetik;
      if ($total > 0) {
        $p_visual = round(($visual / $total) * 100);
        $p_auditori = round(($auditori / $total) * 100);
        $p_kinestetik = round(($kinestetik / $total) * 100);
      } else {
        $p_visual = $p_auditori = $p_kinestetik = 0;
      }
      ?>

      <div class="progress">
        <p>Visual: <?= $p_visual ?>%</p>
        <div style="background:#3b82f6;height:10px;width:<?= $p_visual ?>%;"></div>
        <p>Auditori: <?= $p_auditori ?>%</p>
        <div style="background:#f59e0b;height:10px;width:<?= $p_auditori ?>%;"></div>
        <p>Kinestetik: <?= $p_kinestetik ?>%</p>
        <div style="background:#10b981;height:10px;width:<?= $p_kinestetik ?>%;"></div>
      </div>

      <div style="margin-top:16px;">
        <?php if ($saved): ?>
          <a href="history.php" class="btn-primary">Lihat Riwayat Tes</a>
        <?php else: ?>
          <?php if ($user_id === null): ?>
            <p>Hasil ditampilkan untuk kamu, namun untuk menyimpan hasil silakan <a href="login.php">login</a> terlebih dahulu.</p>
          <?php else: ?>
            <p>Hasil ditampilkan, namun terjadi masalah saat menyimpan riwayat. Silakan coba lagi nanti.</p>
            <?php if (!empty($insert_error)): ?>
              <pre style="background:#fee;border:1px solid #f99;padding:8px;margin-top:8px;color:#900;">Debug: <?= htmlspecialchars($insert_error) ?></pre>
            <?php endif; ?>
          <?php endif; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
</body>
</html>
<?php
}