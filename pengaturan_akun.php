<?php
require_once 'helper/function.php';
cek_login();

$user_id = $_SESSION['user_id'];
$messages = [];

// Ambil data user
$stmt = mysqli_prepare($koneksi, "SELECT tema, notif FROM user WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $tema, $notif);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

// Simpan perubahan pengaturan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $tema_baru = $_POST['tema'] ?? 'terang';
  $notif_baru = isset($_POST['notif']) ? 1 : 0;

  $update = mysqli_prepare($koneksi, "UPDATE user SET tema = ?, notif = ? WHERE id = ?");
  mysqli_stmt_bind_param($update, "sii", $tema_baru, $notif_baru, $user_id);
  mysqli_stmt_execute($update);
  mysqli_stmt_close($update);

  $_SESSION['tema'] = $tema_baru;
  $_SESSION['notif'] = $notif_baru;

  $tema = $tema_baru;
  $notif = $notif_baru;

  $messages[] = ['type'=>'success', 'text'=>'Pengaturan berhasil disimpan.'];
  echo "<script>setTimeout(()=>location.reload(),800);</script>";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pengaturan Akun - Sistem Pakar Gaya Belajar</title>
  <link rel="stylesheet" href="asset/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    * { transition: background-color 0.3s, color 0.3s; }

    .switch { position: relative; display: inline-block; width: 60px; height: 30px; }
    .switch input { opacity: 0; width: 0; height: 0; }
    .slider {
      position: absolute; cursor: pointer;
      top: 0; left: 0; right: 0; bottom: 0;
      background-color: #ccc; transition: .4s; border-radius: 30px;
    }
    .slider:before {
      position: absolute; content: "";
      height: 22px; width: 22px; left: 4px; bottom: 4px;
      background-color: white; transition: .4s; border-radius: 50%;
    }
    input:checked + .slider { background-color: #2563eb; }
    input:checked + .slider:before { transform: translateX(28px); }

    body.dark-mode { background-color: #0f172a; color: #e2e8f0; }
    body.dark-mode .card { background-color: #1e293b; color: #e2e8f0; box-shadow: 0 0 15px rgba(0,0,0,0.4); }
    body.dark-mode .btn { background-color: #3b82f6; color: #fff; }

    .alert { padding: 12px 18px; margin-bottom: 15px; border-radius: 8px; }
    .alert-success { background-color: #dcfce7; color: #166534; }
    .alert-error { background-color: #fee2e2; color: #991b1b; }

    .toggle-theme {
      display: flex; align-items: center; justify-content: space-between;
      margin: 15px 0; padding: 10px 15px;
      background-color: #f1f5f9; border-radius: 10px;
    }
    body.dark-mode .toggle-theme { background-color: #334155; }
  </style>
</head>

<body class="<?= tema_aktif() === 'gelap' ? 'dark-mode' : '' ?>">
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
        <form method="POST">
          <!-- Hidden agar terang tetap terkirim -->
          <input type="hidden" name="tema" value="terang">

          <div class="toggle-theme">
            <span><i class="fa-solid fa-moon"></i> Mode Gelap</span>
            <label class="switch">
              <input type="checkbox" name="tema" value="gelap" <?= ($tema === 'gelap') ? 'checked' : '' ?> onchange="toggleTheme(this)">
              <span class="slider"></span>
            </label>
          </div>

          <label style="display:flex;align-items:center;gap:8px;margin-top:20px;">
            <input type="checkbox" name="notif" <?= ($notif) ? 'checked' : '' ?>>
            Aktifkan notifikasi hasil tes melalui email
          </label><br><br>

          <hr style="margin:20px 0;">

          <p>Untuk mengganti password, silakan buka halaman <a href="edit_profil.php">Edit Profil</a>.</p>

          <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
        </form>
      </div>
    </div>
  </div>

  <script src="asset/js/script.js"></script>
  <script>
    function toggleTheme(el) {
      document.body.classList.toggle("dark-mode", el.checked);
      el.value = el.checked ? "gelap" : "terang";
    }
  </script>
</body>
</html>
