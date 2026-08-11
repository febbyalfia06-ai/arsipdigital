<?php
require_once '../config.php';
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'lurah') {
    header("Location: ../index.php");
    exit();
}
include '../header.php';

// Ambil jumlah pengguna
$total_admin = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM users WHERE role='admin'"))['c'];
$total_sekretaris = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM users WHERE role='sekretaris'"))['c'];
$total_lurah = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM users WHERE role='lurah'"))['c'];

// Ambil performa proses (Arsip yang belum divalidasi vs divalidasi)
$pending_masuk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM surat_masuk WHERE status='Pending' OR status='' OR status IS NULL"))['c'];
$pending_keluar = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM surat_keluar WHERE status='Pending' OR status='' OR status IS NULL"))['c'];
$total_pending = $pending_masuk + $pending_keluar;

$selesai_masuk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM surat_masuk WHERE status='Disetujui Lurah'"))['c'];
$selesai_keluar = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM surat_keluar WHERE status='Disetujui Lurah'"))['c'];
$total_selesai = $selesai_masuk + $selesai_keluar;

?>

<div class="header-action">
    <h2 class="page-title" style="margin-bottom:0;">Monitoring Kinerja Sistem</h2>
</div>

<div class="stats-grid">
    <div class="card" style="text-align: center; padding: 30px;">
        <i class="fas fa-users" style="font-size: 40px; color: #3b82f6; margin-bottom: 15px;"></i>
        <h3 style="margin: 0 0 10px 0; font-size: 18px; color: #1f2937;">Pengguna Sistem</h3>
        <p style="margin: 0; color: #6b7280; font-size: 15px;">Admin: <strong><?= $total_admin ?></strong> | Sekretaris: <strong><?= $total_sekretaris ?></strong> | Lurah: <strong><?= $total_lurah ?></strong></p>
    </div>
    
    <div class="card" style="text-align: center; padding: 30px;">
        <i class="fas fa-hourglass-half" style="font-size: 40px; color: #f59e0b; margin-bottom: 15px;"></i>
        <h3 style="margin: 0 0 10px 0; font-size: 20px; color: #1f2937;">Menunggu Diproses</h3>
        <p style="margin: 0; color: #6b7280; font-size: 15px;">Terdapat <strong><?= $total_pending ?></strong> arsip belum divalidasi.</p>
    </div>
    
    <div class="card" style="text-align: center; padding: 30px;">
        <i class="fas fa-check-circle" style="font-size: 40px; color: #10b981; margin-bottom: 15px;"></i>
        <h3 style="margin: 0 0 10px 0; font-size: 20px; color: #1f2937;">Tugas Selesai</h3>
        <p style="margin: 0; color: #6b7280; font-size: 15px;">Total <strong><?= $total_selesai ?></strong> arsip telah selesai hingga tingkat akhir.</p>
    </div>
</div>

<div class="card" style="margin-top: 20px;">
    <h3 style="margin-bottom: 15px;">Informasi Kinerja</h3>
    <p style="color: #4b5563; line-height: 1.6;">
        Halaman ini memonitor beban kerja saat ini. Jika jumlah "Menunggu Diproses" (Pending) terlalu besar, staf Administrasi dan Sekretaris mungkin memerlukan bantuan atau pengingat untuk segera menyelesaikan validasi dokumen masuk maupun keluar agar pelayanan ke warga tidak terhambat.
    </p>
</div>

<?php include '../footer.php'; ?>
