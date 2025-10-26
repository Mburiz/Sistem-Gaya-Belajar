<?php
include('helper/function.php');
cek_login();

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $user_id = $_SESSION['user_id'];

    // Pastikan hanya menghapus data milik user yang login
    $query = mysqli_prepare($koneksi, "DELETE FROM hasil_tes WHERE id = ? AND user_id = ?");
    mysqli_stmt_bind_param($query, "ii", $id, $user_id);
    mysqli_stmt_execute($query);

    if (mysqli_stmt_affected_rows($query) > 0) {
        header("Location: history.php?deleted=1");
        exit;
    } else {
        header("Location: history.php?error=1");
        exit;
    }
} else {
    header("Location: history.php");
    exit;
}
?>
