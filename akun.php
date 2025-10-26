<?php
require_once 'helper/function.php';
cek_login();
$user_id = $_SESSION['user_id'];

// inisialisasi default
$nama = $email = '';

// ambil data user -- periksa prepare gagal
$stmt = mysqli_prepare($koneksi, "SELECT nama, email FROM user WHERE id = ?");
if ($stmt) {
  mysqli_stmt_bind_param($stmt, "i", $user_id);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_bind_result($stmt, $nama, $email);
  mysqli_stmt_fetch($stmt);
  mysqli_stmt_close($stmt);
} else {
  // fallback: jalankan query langsung (lebih toleran) dan log error
  error_log('prepare failed: ' . mysqli_error($koneksi));
  $res = mysqli_query($koneksi, "SELECT nama, email FROM user WHERE id = " . (int)$user_id);
  if ($res && mysqli_num_rows($res) > 0) {
    $row = mysqli_fetch_assoc($res);
    $nama = $row['nama'] ?? '';
    $email = $row['email'] ?? '';
  }
}

// ambil 5 riwayat tes terakhir
// ambil 5 riwayat tes terakhir
$riwayat = [];
// gunakan kolom `hasil` (sesuai struktur DB)
$q = mysqli_prepare($koneksi, "SELECT hasil, tanggal FROM hasil_tes WHERE user_id = ? ORDER BY tanggal DESC LIMIT 5");
if ($q) {
  mysqli_stmt_bind_param($q, "i", $user_id);
  mysqli_stmt_execute($q);
  mysqli_stmt_bind_result($q, $tipe, $tanggal);
    while (mysqli_stmt_fetch($q)) {
      $riwayat[] = ['tipe' => $tipe, 'tanggal' => $tanggal];
    }
  mysqli_stmt_close($q);
} else {
  error_log('prepare failed (riwayat): ' . mysqli_error($koneksi));
  // fallback ke mysqli_query
  $user_id_int = (int)$user_id;
  $res2 = mysqli_query($koneksi, "SELECT hasil, tanggal FROM hasil_tes WHERE user_id = $user_id_int ORDER BY tanggal DESC LIMIT 5");
  if ($res2) {
    while ($row = mysqli_fetch_assoc($res2)) {
      $riwayat[] = ['tipe' => $row['hasil'] ?? '', 'tanggal' => $row['tanggal'] ?? ''];
    }
  }
}

include 'layout/sidebar.php';
include 'layout/header.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Akun Saya - Sistem Pakar Gaya Belajar</title>
  <link rel="stylesheet" href="asset/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
  <div class="main-content">
    <h1>Akun Saya</h1>
    <p>Kelola informasi profil dan lihat riwayat tes kamu di sini.</p>

    <div class="akun-container">
      <div class="akun-card profil">
        <h2><i class="fa fa-user-circle"></i> Profil Pengguna</h2>
        <p><strong>Nama:</strong> <?= htmlspecialchars($nama) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($email) ?></p>
        <a href="edit_profil.php" class="btn-primary">Edit Profil</a>
        <a href="pengaturan_akun.php" class="btn-primary">Pengaturan Akun</a>
      </div>

      <div class="akun-card riwayat">
        <h2><i class="fa fa-history"></i> Riwayat Tes Terbaru</h2>
        <?php if (count($riwayat) > 0): ?>
          <ul>
            <?php foreach ($riwayat as $r): ?>
              <li>
                <span class="tipe"><?= htmlspecialchars($r['tipe']) ?></span>
                <span class="tanggal"><?= htmlspecialchars($r['tanggal']) ?></span>
              </li>
            <?php endforeach; ?>
          </ul>
          <a href="history.php" class="btn-secondary">Lihat Semua Riwayat</a>
        <?php else: ?>
          <p>Belum ada riwayat tes.</p>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <script src="asset/js/script.js"></script>
</body>
</html>
