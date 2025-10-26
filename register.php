<?php
// register.php
require_once 'helper/function.php'; // koneksi tersedia di $koneksi
session_start();

// Jika sudah login, redirect ke dashboard
if (isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit;
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nama = trim($_POST['nama']);
  $email = trim($_POST['email']);
  $password = $_POST['password'];
  $password2 = $_POST['password2'];

  if ($nama === '' || $email === '' || $password === '') {
    $errors[] = "Semua field wajib diisi.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Format email tidak valid.";
  } elseif ($password !== $password2) {
    $errors[] = "Konfirmasi password tidak cocok.";
  } else {
    // cek apakah email sudah terdaftar
    $stmt = mysqli_prepare($koneksi, "SELECT id FROM user WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    if (mysqli_stmt_num_rows($stmt) > 0) {
      $errors[] = "Email sudah terdaftar. Gunakan email lain atau login.";
    } else {
      $hash = password_hash($password, PASSWORD_DEFAULT);
      $ins = mysqli_prepare($koneksi, "INSERT INTO user (nama, email, password) VALUES (?, ?, ?)");
      mysqli_stmt_bind_param($ins, "sss", $nama, $email, $hash);
      if (mysqli_stmt_execute($ins)) {
        header("Location: login.php?registered=1");
        exit;
      } else {
        $errors[] = "Gagal mendaftar, coba lagi.";
      }
    }
    mysqli_stmt_close($stmt);
  }
}
?>

<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Register - Sistem Pakar</title>
  <link rel="stylesheet" href="asset/css/style.css">
</head>
<body>
<div class="auth-card card" style="max-width:420px;margin:40px auto;">
  <h2>Daftar Akun</h2>

  <?php if (!empty($errors)): ?>
    <div class="errors" style="color:#b91c1c;margin-bottom:12px;">
      <?php foreach($errors as $e) echo "<div>- ".htmlspecialchars($e)."</div>"; ?>
    </div>
  <?php endif; ?>

  <form method="POST" action="register.php">
    <label>Nama Lengkap</label><br>
    <input type="text" name="nama" value="<?= isset($nama) ? htmlspecialchars($nama) : '' ?>" required><br><br>

    <label>Email</label><br>
    <input type="email" name="email" value="<?= isset($email) ? htmlspecialchars($email) : '' ?>" required><br><br>

    <label>Password</label><br>
    <input type="password" name="password" required><br><br>

    <label>Konfirmasi Password</label><br>
    <input type="password" name="password2" required><br><br>

    <button type="submit" class="btn">Daftar</button>
  </form>

  <p style="margin-top:12px;">Sudah punya akun? <a href="login.php">Login di sini</a></p>
</div>
</body>
</html>
