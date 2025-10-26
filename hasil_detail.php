<?php
require_once 'helper/function.php';
cek_login();

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header('Location: login.php');
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header('Location: history.php');
    exit;
}

$stmt = mysqli_prepare($koneksi, "SELECT id, hasil, skor_visual, skor_auditori, skor_kinestetik, tanggal FROM hasil_tes WHERE id = ? AND user_id = ?");
mysqli_stmt_bind_param($stmt, 'ii', $id, $user_id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($res);
mysqli_stmt_close($stmt);

if (!$row) {
    header('Location: history.php');
    exit;
}

// include layout BEFORE the HTML output so posisi UI konsisten dengan history.php
include('layout/sidebar.php');
include('layout/header.php');
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Detail Hasil Tes - Sistem Pakar Gaya Belajar</title>
  <link rel="stylesheet" href="asset/css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    .main-content {max-width:960px;margin:20px auto;padding:18px;box-sizing:border-box;}
    .result-card{background:#fff;padding:16px;border-radius:8px;box-shadow:0 6px 18px rgba(0,0,0,0.04);}
    .result-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;}
    .meta{color:#666;font-size:13px;}
    .scores{display:flex;gap:12px;margin:12px 0;flex-wrap:wrap;}
    .score{flex:1 1 140px;background:#f6f8fb;padding:10px;border-radius:6px;text-align:center;min-width:100px;}
    .score .label{display:block;color:#555;font-weight:600;}
    .score .value{font-size:20px;font-weight:700;margin-top:6px;}
    .charts{display:grid;grid-template-columns:1fr;gap:12px;margin-top:12px;}
    .chart-box{padding:10px;border-radius:8px;background:#fff;box-shadow:0 2px 8px rgba(0,0,0,0.03);min-height:240px;display:flex;flex-direction:column;justify-content:center;}
    .chart-title{text-align:center;font-weight:700;margin-bottom:8px;}
    .btn-back{display:inline-block;margin-top:14px;padding:8px 12px;background:#2b7cff;color:#fff;border-radius:6px;text-decoration:none;}
    @media(min-width:980px){.charts{grid-template-columns:1fr 1fr;}}
    @media(max-width:720px){.scores{flex-direction:column;}.charts{grid-template-columns:1fr;}}
  </style>
</head>
<body>
  <div class="main-content">
    <div class="result-card" role="main" aria-labelledby="title">
      <div class="result-head">
        <h2 id="title">Detail Hasil Tes Gaya Belajar</h2>
        <div class="meta">Tanggal: <?= htmlspecialchars($row['tanggal']); ?></div>
      </div>

      <div><strong>Hasil Dominan:</strong> <?= htmlspecialchars($row['hasil']); ?></div>

      <div class="scores" role="list" aria-label="Skor">
        <div class="score" role="listitem">
          <span class="label">Visual</span>
          <span class="value"><?= (int)$row['skor_visual']; ?></span>
        </div>
        <div class="score" role="listitem">
          <span class="label">Auditori</span>
          <span class="value"><?= (int)$row['skor_auditori']; ?></span>
        </div>
        <div class="score" role="listitem">
          <span class="label">Kinestetik</span>
          <span class="value"><?= (int)$row['skor_kinestetik']; ?></span>
        </div>
      </div>

      <div class="charts">
        <div class="chart-box">
          <div class="chart-title">Distribusi Gaya Belajar (Pie)</div>
          <canvas id="pieChart"></canvas>
        </div>

        <div class="chart-box">
          <div class="chart-title">Perbandingan Skor (Bar)</div>
          <canvas id="barChart"></canvas>
        </div>
      </div>

      <a href="history.php" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
    </div>
  </div>
  <script>
    (function(){
      function adjustMainOffset(){
        var header = document.querySelector('header') || document.querySelector('.header') || document.getElementById('header');
        var main = document.querySelector('.main-content');
        if (!header || !main) return;
        var h = header.getBoundingClientRect().height || 0;
        // add small extra gap (16px) so judul tidak menempel ke header
        main.style.marginTop = (h + 16) + 'px';
      }
      window.addEventListener('load', adjustMainOffset);
      window.addEventListener('resize', adjustMainOffset);
      // in case layout is modified after load (e.g. sidebar toggles)
      setTimeout(adjustMainOffset, 500);
    })();


    (function(){
      const v = <?= (int)$row['skor_visual']; ?>;
      const a = <?= (int)$row['skor_auditori']; ?>;
      const k = <?= (int)$row['skor_kinestetik']; ?>;
      const labels = ['Visual','Auditori','Kinestetik'];
      const data = [v,a,k];
      const colors = ['#36A2EB','#FF6384','#4BC0C0'];

      new Chart(document.getElementById('pieChart'), {
        type: 'pie',
        data: { labels, datasets:[{ data, backgroundColor: colors, borderColor:'#fff', borderWidth:1 }] },
        options: {
          responsive:true,
          plugins:{
            legend:{position:'bottom'},
            tooltip:{callbacks:{label(ctx){
              const total = data.reduce((s,x)=>s+x,0)||1;
              const val = ctx.raw||0;
              return `${ctx.label}: ${val} (${((val/total)*100).toFixed(1)}%)`;
            }}}
          }
        }
      });

      new Chart(document.getElementById('barChart'), {
        type: 'bar',
        data: { labels, datasets:[{ label:'Skor', data, backgroundColor: colors, borderColor: colors, borderWidth:1 }] },
        options: {
          responsive:true,
          scales:{ y:{ beginAtZero:true, ticks:{stepSize:1}, suggestedMax:8 }},
          plugins:{ legend:{display:false} }
        }
      });
    })();
  </script>
  <script src="asset/js/script.js"></script>
</body>
</html>