<?php
require_once '../config.php';
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'lurah') {
    header("Location: ../index.php");
    exit();
}
include '../header.php';

// Ambil data untuk membuat chart sederhana berbasis HTML/CSS (Visualisasi)
$q_masuk = mysqli_query($conn, "SELECT status, COUNT(*) as jml FROM surat_masuk GROUP BY status");
$stats_masuk = [];
while($r = mysqli_fetch_assoc($q_masuk)) {
    $status = empty($r['status']) ? 'Pending' : $r['status'];
    $stats_masuk[$status] = $r['jml'];
}

$q_keluar = mysqli_query($conn, "SELECT status, COUNT(*) as jml FROM surat_keluar GROUP BY status");
$stats_keluar = [];
while($r = mysqli_fetch_assoc($q_keluar)) {
    $status = empty($r['status']) ? 'Pending' : $r['status'];
    $stats_keluar[$status] = $r['jml'];
}

function renderBar($label, $value, $max, $color) {
    if($max == 0) $max = 1; // avoid division by zero
    $pct = round(($value / $max) * 100);
    if($pct == 0 && $value > 0) $pct = 1;
    
    echo "<div style='margin-bottom: 10px;'>
            <div style='display:flex; justify-content:space-between; margin-bottom:5px; font-size:13px; color:#4b5563;'>
                <span>$label</span>
                <strong>$value</strong>
            </div>
            <div style='width:100%; background:#f3f4f6; border-radius:10px; height:20px; overflow:hidden;'>
                <div style='width:$pct%; background:$color; height:100%; transition: width 0.5s;'></div>
            </div>
          </div>";
}
?>

<div class="header-action">
    <h2 class="page-title" style="margin-bottom:0;">Statistik Arsip</h2>
</div>

<div class="stats-grid">
    <div class="card">
        <h3 style="margin-bottom: 20px;"><i class="fas fa-inbox text-primary"></i> Statistik Arsip Masuk</h3>
        <?php 
        $max_m = array_sum($stats_masuk);
        if($max_m > 0) {
            renderBar('Pending', isset($stats_masuk['Pending']) ? $stats_masuk['Pending'] : 0, $max_m, '#9ca3af');
            renderBar('Divalidasi', isset($stats_masuk['Divalidasi']) ? $stats_masuk['Divalidasi'] : 0, $max_m, '#3b82f6');
            renderBar('Dikembalikan', isset($stats_masuk['Dikembalikan']) ? $stats_masuk['Dikembalikan'] : 0, $max_m, '#ef4444');
            renderBar('Dikirim ke Lurah', isset($stats_masuk['Dikirim ke Lurah']) ? $stats_masuk['Dikirim ke Lurah'] : 0, $max_m, '#f59e0b');
            renderBar('Disetujui Lurah', isset($stats_masuk['Disetujui Lurah']) ? $stats_masuk['Disetujui Lurah'] : 0, $max_m, '#10b981');
            renderBar('Ditolak Lurah', isset($stats_masuk['Ditolak Lurah']) ? $stats_masuk['Ditolak Lurah'] : 0, $max_m, '#b91c1c');
        } else {
            echo "<p style='color:#666;'>Belum ada data.</p>";
        }
        ?>
    </div>
    
    <div class="card">
        <h3 style="margin-bottom: 20px;"><i class="fas fa-paper-plane text-success"></i> Statistik Arsip Keluar</h3>
        <?php 
        $max_k = array_sum($stats_keluar);
        if($max_k > 0) {
            renderBar('Pending', isset($stats_keluar['Pending']) ? $stats_keluar['Pending'] : 0, $max_k, '#9ca3af');
            renderBar('Divalidasi', isset($stats_keluar['Divalidasi']) ? $stats_keluar['Divalidasi'] : 0, $max_k, '#3b82f6');
            renderBar('Dikembalikan', isset($stats_keluar['Dikembalikan']) ? $stats_keluar['Dikembalikan'] : 0, $max_k, '#ef4444');
            renderBar('Dikirim ke Lurah', isset($stats_keluar['Dikirim ke Lurah']) ? $stats_keluar['Dikirim ke Lurah'] : 0, $max_k, '#f59e0b');
            renderBar('Disetujui Lurah', isset($stats_keluar['Disetujui Lurah']) ? $stats_keluar['Disetujui Lurah'] : 0, $max_k, '#10b981');
            renderBar('Ditolak Lurah', isset($stats_keluar['Ditolak Lurah']) ? $stats_keluar['Ditolak Lurah'] : 0, $max_k, '#b91c1c');
        } else {
            echo "<p style='color:#666;'>Belum ada data.</p>";
        }
        ?>
    </div>
</div>

<?php include '../footer.php'; ?>
