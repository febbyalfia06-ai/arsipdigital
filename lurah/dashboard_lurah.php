<?php
require_once '../config.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'lurah') {
    header("Location: ../index.php");
    exit();
}
include '../header.php';

// Menentukan tipe data yang dipilih
$tipe = isset($_GET['tipe']) ? $_GET['tipe'] : 'masuk';
$table = ($tipe == 'keluar') ? 'surat_keluar' : 'surat_masuk';

// Menentukan daftar kategori berdasarkan tipe
$categories = [];
if ($tipe == 'keluar') {
    $categories = [
        'Surat keterangan domisili',
        'Surat keterangan tidak mampu (SKTM)',
        'Surat keterangan Usaha (SKU)',
        'Surat pengantar akta kematian',
        'Surat keterangan belum nikah',
        'Surat keterangan kematian',
        'Surat pengantar nikah N1 (Islam)',
        'Surat pengantar nikah N2 (Hindu)',
        'Surat pengantar nikah N3 (Kristen Protestan)',
        'Surat pengantar nikah N4 (Kristen)',
        'Surat undangan',
        'Surat keterangan biasa',
        'Perwalian nikah',
        'Surat keterangan nama',
        'Surat tugas'
    ];
} else {
    $categories = [
        'Surat dari kecamatan',
        'Surat dari pemerintah kota',
        'Surat dari instansi pemerintah lainnya',
        'Surat dari RT/RW',
        'Surat permohonan dari masyarakat',
        'Surat undangan',
        'Surat edaran',
        'Surat pemberitahuan'
    ];
}

// Mengambil total semua dokumen untuk tipe yang aktif
$q_total = mysqli_query($conn, "SELECT COUNT(*) as total FROM $table");
$r_total = mysqli_fetch_assoc($q_total);
$total_all = $r_total['total'];

// Mengambil total per kategori
$counts = [];
foreach($categories as $cat) {
    $escaped_cat = mysqli_real_escape_string($conn, $cat);
    $q = mysqli_query($conn, "SELECT COUNT(*) as c FROM $table WHERE kategori = '$escaped_cat'");
    $r = mysqli_fetch_assoc($q);
    $counts[$cat] = $r['c'];
}
?>

<div class="header-action">
    <h2 class="page-title" style="margin-bottom:0;">Dashboard Statistik</h2>
    <div>
        <form method="GET" action="">
            <select name="tipe" class="form-control" onchange="this.form.submit()" style="width:250px; display:inline-block; font-weight:bold;">
                <option value="masuk" <?= $tipe == 'masuk' ? 'selected' : '' ?>>📊 Kategori Data Arsip Masuk</option>
                <option value="keluar" <?= $tipe == 'keluar' ? 'selected' : '' ?>>📊 Kategori Data Arsip Keluar</option>
            </select>
        </form>
    </div>
</div>

<div class="card" style="margin-bottom: 20px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 30px; border-radius: 12px; display:flex; align-items:center; gap:25px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); flex-wrap:wrap;">
    <img src="../assets/logo_dashboard.png" alt="Logo Kelurahan Pengasinan" style="width:130px; height:auto; flex-shrink:0; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.2)); border-radius: 12px;">
    <div>
        <h3 style="margin-top: 0; font-size: 1.4rem; font-weight: normal; opacity: 0.9;">
            Pemerintah Kota Bekasi
        </h3>
        <div style="font-size: 2.2rem; font-weight: bold;">
            Kelurahan Pengasinan
        </div>
        <p style="margin: 10px 0 0 0; font-size: 1rem; opacity: 0.9;">
            Sistem Informasi Arsip Digital Dokumen Kependudukan
        </p>
    </div>
</div>

<div class="card" style="margin-bottom: 20px; background: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border-left: 5px solid #3b82f6;">
    <h3 style="margin-top: 0; font-size: 1.2rem; font-weight: normal; color:var(--text-muted);">
        Total Dokumen pada <?= $tipe == 'keluar' ? 'Arsip Keluar' : 'Arsip Masuk' ?>
    </h3>
    <div style="font-size: 2.5rem; font-weight: bold; color:var(--text-main);">
        <?= $total_all ?> <span style="font-size: 1rem; font-weight: normal; color:var(--text-muted);">Arsip</span>
    </div>
</div>

<h3 style="margin-top:30px; margin-bottom:15px; color:var(--text-main); font-size:1.1rem;">Rincian Total Per Kategori:</h3>

<div class="stats-grid" style="grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));">
    <?php foreach($categories as $cat): ?>
        <div class="stat-card" style="border-left: 4px solid #3b82f6; min-height: 120px; align-items: flex-start; padding: 15px;">
            <div class="stat-info" style="width: 100%;">
                <h3 style="font-size: 0.9rem; line-height: 1.4; margin-bottom: 10px; color: #4b5563;"><?= htmlspecialchars($cat) ?></h3>
                <p class="num" style="font-size: 1.8rem; color: #1f2937;"><?= $counts[$cat] ?></p>
            </div>
        </div>
    <?php endforeach; ?>
</div>



<?php include '../footer.php'; ?>
