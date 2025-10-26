<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sistem Pakar Gaya Belajar</title>
  <link rel="stylesheet" href="asset/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
  <?php include 'layout/sidebar.php'; ?>
  <?php include 'layout/header.php'; ?>

  <div class="main-content">
    <div class="welcome-section">
      <h1>Selamat Datang!</h1>
      <p class="welcome-text">Yuk coba tes gaya belajar kamu!</p>
    </div>

    <div class="learning-styles">
      <div class="style-card visual">
        <div class="icon">
          <i class="fa-solid fa-eye"></i>
        </div>
        <div class="text">
          <h3>Visual</h3>
          <p>Belajar lewat gambar, warna, video, dan diagram. Kamu lebih mudah memahami sesuatu jika melihatnya secara visual.</p>
        </div>
      </div>

      <div class="style-card auditory">
        <div class="icon">
          <i class="fa-solid fa-headphones"></i>
        </div>
        <div class="text">
          <h3>Auditori</h3>
          <p>Belajar efektif dengan mendengarkan penjelasan, musik, atau berdiskusi. Kamu cepat paham lewat suara atau pembicaraan.</p>
        </div>
      </div>

      <div class="style-card kinesthetic">
        <div class="icon">
          <i class="fa-solid fa-person-running"></i>
        </div>
        <div class="text">
          <h3>Kinestetik</h3>
          <p>Belajar dengan praktik langsung, aktivitas fisik, dan gerakan. Kamu lebih mudah mengingat hal yang dilakukan secara nyata.</p>
        </div>
      </div>
    </div>
  </div>

  <script src="asset/js/script.js"></script>
</body>
</html>
