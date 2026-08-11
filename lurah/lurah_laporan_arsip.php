<?php
require_once '../config.php';
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'lurah') {
    header("Location: ../index.php");
    exit();
}
include '../header.php';

// Menghitung statistik untuk Laporan Arsip
$q_masuk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM surat_masuk"));
$q_keluar = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM surat_keluar"));

$q_masuk_valid = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM surat_masuk WHERE status = 'Disetujui Lurah'"));
$q_keluar_valid = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM surat_keluar WHERE status = 'Disetujui Lurah'"));
?>

<div class="header-action">
    <h2 class="page-title" style="margin-bottom:0;">Laporan Arsip (Tingkat Lurah)</h2>
    <button class="btn" style="background:#10b981;" onclick="window.print()"><i class="fas fa-print"></i> Cetak Laporan</button>
</div>

<div class="card" style="margin-bottom: 20px;">
    <h3 style="margin-bottom: 20px;">Statistik Arsip (Telah Disetujui)</h3>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
        <div style="background: #eff6ff; border-left: 4px solid #3b82f6; padding: 20px; border-radius: 4px;">
            <p style="color: #475569; font-size: 14px; margin: 0 0 5px 0;">Total Arsip Masuk (Semua Status)</p>
            <h2 style="color: #1e293b; margin: 0; font-size: 28px;"><?= $q_masuk['total'] ?></h2>
        </div>
        <div style="background: #f0fdf4; border-left: 4px solid #10b981; padding: 20px; border-radius: 4px;">
            <p style="color: #475569; font-size: 14px; margin: 0 0 5px 0;">Arsip Masuk (Disetujui Lurah)</p>
            <h2 style="color: #1e293b; margin: 0; font-size: 28px;"><?= $q_masuk_valid['total'] ?></h2>
        </div>
        <div style="background: #fefce8; border-left: 4px solid #eab308; padding: 20px; border-radius: 4px;">
            <p style="color: #475569; font-size: 14px; margin: 0 0 5px 0;">Total Arsip Keluar (Semua Status)</p>
            <h2 style="color: #1e293b; margin: 0; font-size: 28px;"><?= $q_keluar['total'] ?></h2>
        </div>
        <div style="background: #f0fdf4; border-left: 4px solid #10b981; padding: 20px; border-radius: 4px;">
            <p style="color: #475569; font-size: 14px; margin: 0 0 5px 0;">Arsip Keluar (Disetujui Lurah)</p>
            <h2 style="color: #1e293b; margin: 0; font-size: 28px;"><?= $q_keluar_valid['total'] ?></h2>
        </div>
    </div>
</div>

<div class="card">
    <h3 style="margin-bottom: 20px;">Daftar Rekap Arsip Terakhir Disetujui</h3>
    <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th style="width:50px;">No</th>
                    <th>Nomor Surat</th>
                    <th>Nama Dokumen</th>
                    <th>Tipe</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                // Gabung arsip masuk dan keluar yang disetujui
                $query = mysqli_query($conn, "
                    SELECT nomor_surat, perihal, status, 'Arsip Masuk' as tipe, id FROM surat_masuk WHERE status = 'Disetujui Lurah'
                    UNION 
                    SELECT nomor_surat, perihal, status, 'Arsip Keluar' as tipe, id FROM surat_keluar WHERE status = 'Disetujui Lurah'
                    ORDER BY id DESC LIMIT 20
                ");
                if(mysqli_num_rows($query) > 0) {
                    while($row = mysqli_fetch_assoc($query)) {
                        echo "<tr>
                            <td>{$no}</td>
                            <td>".htmlspecialchars($row['nomor_surat'])."</td>
                            <td>".htmlspecialchars($row['perihal'])."</td>
                            <td><span class='badge' style='background:#f3f4f6; color:#4b5563;'>".htmlspecialchars($row['tipe'])."</span></td>
                            <td><span style='color:#10b981; font-weight:500;'><i class='fas fa-check-double'></i> ".htmlspecialchars($row['status'])."</span></td>
                        </tr>";
                        $no++;
                    }
                } else {
                    echo "<tr><td colspan='5' style='text-align:center;'>Belum ada rekapan data arsip yang disetujui.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../footer.php'; ?>
