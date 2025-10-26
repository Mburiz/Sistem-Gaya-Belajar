<?php
$koneksi = mysqli_connect("localhost", "root", "", "gaya_belajar");

if (!$koneksi) {
  die("Koneksi gagal: " . mysqli_connect_error());
}

function cek_login() {
  session_start();
  if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
  }
}

function tema_aktif() {
    return $_SESSION['tema'] ?? 'terang';
}

?>
