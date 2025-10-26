<?php
// helper/auth.php
// Fungsi autentikasi user (login, cek, ambil data pengguna)

// Pastikan koneksi sudah ada
require_once __DIR__ . '/koneksi.php';

/**
 * Mengecek apakah user sudah login
 */
function is_logged_in() {
  return isset($_SESSION['user_id']) && $_SESSION['user_id'] > 0;
}

/**
 * Memaksa login — kalau belum login akan diarahkan ke login.php
 */
function require_login() {
  if (!is_logged_in()) {
    header('Location: /login.php');
    exit;
  }
}

/**
 * Mengambil data user saat ini dari database
 */
function current_user($conn) {
  if (!is_logged_in()) return null;

  $id = (int)$_SESSION['user_id'];
  $stmt = $conn->prepare("SELECT id, nama, email, foto, role FROM users WHERE id = ? LIMIT 1");
  $stmt->bind_param('i', $id);
  $stmt->execute();
  $res = $stmt->get_result();
  return $res->fetch_assoc();
}

/**
 * Fungsi login — dipanggil saat user submit form login
 */
function login_user($conn, $email, $password) {
  $stmt = $conn->prepare("SELECT id, nama, email, password, foto, role FROM users WHERE email = ? LIMIT 1");
  $stmt->bind_param('s', $email);
  $stmt->execute();
  $res = $stmt->get_result();

  if ($res->num_rows === 1) {
    $user = $res->fetch_assoc();
    if (password_verify($password, $user['password'])) {
      // Simpan session
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['nama'] = $user['nama'];
      $_SESSION['email'] = $user['email'];
      $_SESSION['foto'] = $user['foto'];
      $_SESSION['role'] = $user['role'];
      return true;
    }
  }
  return false;
}

/**
 * Fungsi logout
 */
function logout_user() {
  session_start();
  session_unset();
  session_destroy();
  header('Location: /login.php');
  exit;
}
?>
