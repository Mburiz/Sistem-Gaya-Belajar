<?php
require_once 'helper/function.php';
session_start();

if (isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email']);
  $password = $_POST['password'];

  if ($email === '' || $password === '') {
    $error = "Email dan password wajib diisi.";
  } else {
    $stmt = mysqli_prepare($koneksi, "SELECT id, nama, password FROM user WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id, $nama, $hash);
    if (mysqli_stmt_fetch($stmt)) {
      if (password_verify($password, $hash)) {
        $_SESSION['user_id'] = $id;
        $_SESSION['nama'] = $nama;
        $_SESSION['email'] = $email;
        header("Location: index.php");
        exit;
      } else {
        $error = "Email atau password salah.";
      }
    } else {
      $error = "Email tidak ditemukan.";
    }
    mysqli_stmt_close($stmt);
  }
}
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Login - Sistem Pakar</title>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #74ABE2, #5563DE);
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .card {
      background: rgba(255, 255, 255, 0.15);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      border-radius: 16px;
      padding: 40px;
      width: 380px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
      color: white;
    }

    h2 {
      text-align: center;
      margin-bottom: 24px;
      font-size: 28px;
    }

    label {
      display: block;
      margin-bottom: 6px;
      font-weight: 500;
    }

    input {
      width: 100%;
      padding: 10px;
      border-radius: 8px;
      border: none;
      margin-bottom: 16px;
      background: rgba(255,255,255,0.85);
      font-size: 15px;
    }

    input:focus {
      outline: 2px solid #5563DE;
      background: white;
    }

    .btn {
      width: 100%;
      background-color: #3742fa;
      color: white;
      border: none;
      padding: 10px;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
      transition: 0.3s;
    }

    .btn:hover {
      background-color: #1e90ff;
    }

    p {
      text-align: center;
      margin-top: 16px;
    }

    a {
      color: #ffd43b;
      text-decoration: none;
      font-weight: bold;
    }

    a:hover {
      text-decoration: underline;
    }

    .error {
      color: #ffbaba;
      background: rgba(255, 0, 0, 0.3);
      padding: 10px;
      border-radius: 8px;
      text-align: center;
      margin-bottom: 16px;
    }

    .success {
      color: #d4edda;
      background: rgba(0, 128, 0, 0.3);
      padding: 10px;
      border-radius: 8px;
      text-align: center;
      margin-bottom: 16px;
    }
  </style>
</head>
<body>
  <div class="card">
    <h2>Login</h2>

    <?php if (!empty($_GET['registered'])): ?>
      <div class="success">Registrasi berhasil. Silakan login.</div>
    <?php endif; ?>

    <?php if ($error): ?>
      <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
      <label>Email</label>
      <input type="email" name="email" required>

      <label>Password</label>
      <input type="password" name="password" required>

      <button type="submit" class="btn">Login</button>
    </form>

    <p>Belum punya akun? <a href="register.php">Daftar</a></p>
  </div>
</body>
</html>
