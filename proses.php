<?php
include('helper/function.php');
session_start();

if (isset($_POST['submit_tes'])) {
  $user_id = $_SESSION['user_id'];
  $jawaban = [$_POST['q1'], $_POST['q2'], $_POST['q3']];
  $tipe = array_count_values($jawaban);
  arsort($tipe);
  $hasil = key($tipe);

  mysqli_query($koneksi, "INSERT INTO hasil_tes (user_id, tipe_gaya, skor) VALUES ('$user_id', '$hasil', 100)");
  header("Location: hasil.php");
}
?>
