<?php
require_once 'helper/function.php';
cek_login();

// Pastikan koneksi mysqli tersedia di helper/function.php sebagai $koneksi
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
  header('Location: login.php');
  exit;
}

// Ambil dan validasi 8 jawaban
$answers = [];
for ($i = 1; $i <= 8; $i++) {
  if (empty($_POST['q' . $i])) {
    header('Location: tes.php?error=missing');
    exit;
  }
  $answers[] = strtolower(trim($_POST['q' . $i]));
}

// Hitung skor
$skor_visual = 0;
$skor_auditori = 0;
$skor_kinestetik = 0;
foreach ($answers as $ans) {
  if ($ans === 'visual') $skor_visual++;
  elseif ($ans === 'auditori') $skor_auditori++;
  elseif ($ans === 'kinestetik') $skor_kinestetik++;
}

// Tentukan hasil dominan (jika seri -> tampilkan gabungan)
$scores = [
  'Visual' => $skor_visual,
  'Auditori' => $skor_auditori,
  'Kinestetik' => $skor_kinestetik
];
$max = max($scores);
$winners = [];
foreach ($scores as $label => $val) {
  if ($val === $max) $winners[] = $label;
}
$hasil = implode('/', $winners);

// Simpan ke database menggunakan prepared statement
$stmt = mysqli_prepare($koneksi, "INSERT INTO hasil_tes (user_id, hasil, skor_visual, skor_auditori, skor_kinestetik) VALUES (?, ?, ?, ?, ?)");
if ($stmt) {
  mysqli_stmt_bind_param($stmt, 'isiii', $user_id, $hasil, $skor_visual, $skor_auditori, $skor_kinestetik);
  $ok = mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);
  if ($ok) {
    header('Location: history.php?saved=1');
    exit;
  } else {
    header('Location: tes.php?error=savefail');
    exit;
  }
} else {
  header('Location: tes.php?error=stmtfail');
  exit;
}
?>