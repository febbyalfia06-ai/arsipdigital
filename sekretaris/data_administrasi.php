<?php
require_once '../config.php';
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'sekretaris') {
    header("Location: ../index.php");
    exit();
}
include '../header.php';
?>

<div class="header-action">
    <h2 class="page-title" style="margin-bottom:0;">Data Administrasi</h2>
</div>

<div class="card">
    <p style="margin-bottom: 15px; color: #666;">Data master administrasi kelurahan.</p>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 20px;">
        <div style="background: #f8fafc; padding: 20px; border-radius: 8px; border: 1px solid #e2e8f0;">
            <h4 style="margin-bottom: 10px; color: #1e293b;"><i class="fas fa-users" style="color:#3b82f6;"></i> Data Penduduk</h4>
            <p style="font-size: 14px; color: #64748b; margin-bottom: 15px;">Informasi rekapitulasi jumlah penduduk.</p>
            <button type="button" onclick="Swal.fire('Data Penduduk', 'Rekapitulasi kependudukan terintegrasi dengan database SIM Kelurahan.', 'info')" class="btn" style="background: #3b82f6; width: 100%; text-align: center; display: block; border:none;">Lihat Detail</button>
        </div>
        <div style="background: #f8fafc; padding: 20px; border-radius: 8px; border: 1px solid #e2e8f0;">
            <h4 style="margin-bottom: 10px; color: #1e293b;"><i class="fas fa-home" style="color:#10b981;"></i> Data RT / RW</h4>
            <p style="font-size: 14px; color: #64748b; margin-bottom: 15px;">Daftar ketua lingkungan RT dan RW.</p>
            <button type="button" onclick="Swal.fire('Data RT / RW', 'Daftar pengurus RT/RW tercantum pada lampiran Surat Kelurahan.', 'info')" class="btn" style="background: #10b981; width: 100%; text-align: center; display: block; border:none;">Lihat Detail</button>
        </div>
        <div style="background: #f8fafc; padding: 20px; border-radius: 8px; border: 1px solid #e2e8f0;">
            <h4 style="margin-bottom: 10px; color: #1e293b;"><i class="fas fa-building" style="color:#f59e0b;"></i> Fasilitas Umum</h4>
            <p style="font-size: 14px; color: #64748b; margin-bottom: 15px;">Data infrastruktur dan fasilitas desa.</p>
            <button type="button" onclick="Swal.fire('Fasilitas Umum', 'Data sarana prasarana kelurahan terdaftar pada fasilitas umum Pengasinan.', 'info')" class="btn" style="background: #f59e0b; width: 100%; text-align: center; display: block; border:none;">Lihat Detail</button>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>
