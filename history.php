<?php
include('helper/function.php'); 
cek_login(); 
include('layout/sidebar.php'); 
include('layout/header.php'); 

$id = $_SESSION['user_id'];
$query = mysqli_query($koneksi, "SELECT * FROM hasil_tes WHERE user_id='$id' ORDER BY tanggal DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Tes - Sistem Pakar Gaya Belajar</title>
    <link rel="stylesheet" href="asset/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <div class="main-content">
        <h1>Riwayat Tes Gaya Belajar</h1>
        <p>Berikut hasil tes gaya belajar kamu sebelumnya.</p>

        <div class="history-container">
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tipe Gaya Belajar</th>
                        <th>Skor Visual</th>
                        <th>Skor Auditori</th>
                        <th>Skor Kinestetik</th>
                        <th>Tanggal Tes</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    while($row = mysqli_fetch_assoc($query)) {
                        $id_row = (int)$row['id'];
                        $hasil = htmlspecialchars($row['hasil']);
                        $tanggal = htmlspecialchars($row['tanggal']);
                        $sv = (int)$row['skor_visual'];
                        $sa = (int)$row['skor_auditori'];
                        $sk = (int)$row['skor_kinestetik'];

                        echo "<tr>
                            <td>{$no}</td>
                            <td>{$hasil}</td>
                            <td class='text-center'>{$sv}</td>
                            <td class='text-center'>{$sa}</td>
                            <td class='text-center'>{$sk}</td>
                            <td>{$tanggal}</td>
                            <td class='text-center'>
                                <a href='hasil_detail.php?id={$id_row}' class='btn-view' title='Lihat detail'>
                                    <i class='fa-solid fa-eye'></i>
                                </a>
                            </td>
                        </tr>";
                        $no++;
                    }
                    if ($no == 1) {
                        echo "<tr><td colspan='7' class='text-center'>Belum ada data tes.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="asset/js/script.js"></script>
</body>
</html>