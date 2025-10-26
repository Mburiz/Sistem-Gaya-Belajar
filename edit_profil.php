<?php
require_once 'helper/function.php';
cek_login();

$user_id = $_SESSION['user_id'];
$messages = [];

// ambil data user sekarang
$stmt = mysqli_prepare($koneksi, "SELECT nama, email, foto FROM user WHERE id = ?");
if (!$stmt) {
  die("Query gagal dipersiapkan: " . mysqli_error($koneksi));
}
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $nama_lama, $email_lama, $foto_lama);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

// Proses update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nama = trim($_POST['nama']);
  $email = trim($_POST['email']);
  $pass = $_POST['password'];
  $pass2 = $_POST['password2'];

  // upload foto jika ada
  $foto_nama = $foto_lama;
  if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $tmp = $_FILES['foto']['tmp_name'];
    $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
    $foto_nama = "asset/img/profile/user_" . $user_id . "_" . time() . "." . $ext;
    move_uploaded_file($tmp, $foto_nama);
  }

  if ($nama === '' || $email === '') {
    $messages[] = ['type'=>'error','text'=>'Nama dan email wajib diisi.'];
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $messages[] = ['type'=>'error','text'=>'Format email tidak valid.'];
  } else {
    // cek email ganda
    $check = mysqli_prepare($koneksi, "SELECT id FROM user WHERE email = ? AND id != ?");
    mysqli_stmt_bind_param($check, "si", $email, $user_id);
    mysqli_stmt_execute($check);
    mysqli_stmt_store_result($check);

    if (mysqli_stmt_num_rows($check) > 0) {
      $messages[] = ['type'=>'error','text'=>'Email sudah digunakan user lain.'];
    } else {
      if ($pass !== '') {
        if ($pass !== $pass2) {
          $messages[] = ['type'=>'error','text'=>'Password konfirmasi tidak cocok.'];
        } else {
          $hash = password_hash($pass, PASSWORD_DEFAULT);
          $up = mysqli_prepare($koneksi, "UPDATE user SET nama=?, email=?, password=?, foto=? WHERE id=?");
          mysqli_stmt_bind_param($up, "ssssi", $nama, $email, $hash, $foto_nama, $user_id);
          mysqli_stmt_execute($up);
          mysqli_stmt_close($up);
          $_SESSION['nama'] = $nama;
          $_SESSION['foto'] = $foto_nama;
          $messages[] = ['type'=>'success','text'=>'Profil & password berhasil diupdate.'];
        }
      } else {
        $up = mysqli_prepare($koneksi, "UPDATE user SET nama=?, email=?, foto=? WHERE id=?");
        mysqli_stmt_bind_param($up, "sssi", $nama, $email, $foto_nama, $user_id);
        mysqli_stmt_execute($up);
        mysqli_stmt_close($up);
  $_SESSION['nama'] = $nama;
  $_SESSION['foto'] = $foto_nama;
        $messages[] = ['type'=>'success','text'=>'Profil berhasil diupdate.'];
      }
    }
    mysqli_stmt_close($check);
  }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Profil - Sistem Pakar Gaya Belajar</title>
  <link rel="stylesheet" href="asset/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
  <?php include 'layout/sidebar.php'; ?>
  <?php include 'layout/header.php'; ?>

  <div class="main-content">
    <div class="container">
      <h2>Pengaturan Akun</h2>

      <?php foreach ($messages as $m): ?>
        <div class="alert <?= $m['type'] === 'error' ? 'alert-error' : 'alert-success' ?>">
          <?= htmlspecialchars($m['text']) ?>
        </div>
      <?php endforeach; ?>

      <div class="card" style="max-width:700px;">
        <form method="POST" enctype="multipart/form-data">
          <div style="display:flex; align-items:center; gap:20px; margin-bottom:20px;">
            <img src="<?= htmlspecialchars($foto_lama ?: 'asset/img/profile/default.png') ?>" 
                 alt="Foto Profil" 
                 style="width:100px;height:100px;border-radius:50%;object-fit:cover;">
            <div>
              <label for="foto" style="font-weight:bold;">Ubah Foto Profil</label><br>
              <input type="file" name="foto" id="foto" accept="image/*">
            </div>
          </div>

          <label>Nama Lengkap</label><br>
          <input type="text" name="nama" value="<?= htmlspecialchars($nama_lama) ?>" required class="input-text"><br><br>

          <label>Email</label><br>
          <input type="email" name="email" value="<?= htmlspecialchars($email_lama) ?>" required class="input-text"><br><br>

          <hr style="margin:20px 0;">

          <p>Biarkan password kosong jika tidak ingin mengganti password</p>

          <label>Password Baru</label><br>
          <input type="password" name="password" class="input-text"><br><br>

          <label>Konfirmasi Password Baru</label><br>
          <input type="password" name="password2" class="input-text"><br><br>

          <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </form>
      </div>
    </div>
  </div>

  <script src="asset/js/script.js"></script>
</body>
</html>
