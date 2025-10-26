<header class="header">
  <?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
  <div class="header-left">
    <button id="toggleSidebarHeader" class="menu-btn">☰</button>
    <h1>Sistem Pakar Gaya Belajar</h1>
  </div>

  <div class="header-right">
    <div class="user-info">
      <?php
      // nama tampilkan jika login, kalau tidak beri link Login
      $user_name = $_SESSION['nama'] ?? null;
      $user_photo = $_SESSION['foto'] ?? null;

      if ($user_name):
        echo '<span>' . htmlspecialchars($user_name) . '</span>';
      else:
        echo '<a href="login.php" style="color:inherit;text-decoration:none;">Login</a>';
      endif;

      // foto: jika session menyimpan hanya filename, prefix path ke folder profile
      if ($user_photo) {
        $photo_path = (strpos($user_photo, 'asset/') === 0) ? $user_photo : 'asset/img/profile/' . ltrim($user_photo, '/');
      } else {
        $photo_path = 'asset/img/profile/default.png';
      }
      ?>

      <img src="<?= htmlspecialchars($photo_path) ?>" class="user-avatar" alt="Foto Profil">
    </div>
  </div>

</header>
