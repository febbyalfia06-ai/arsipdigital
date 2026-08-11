<?php
require_once '../config.php';
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'sekretaris') {
    header("Location: ../index.php");
    exit();
}
include '../header.php';

// Menghitung statistik layanan masyarakat dari arsip keluar
$query_layanan = mysqli_query($conn, "SELECT kategori, COUNT(*) as jumlah FROM surat_keluar GROUP BY kategori ORDER BY jumlah DESC");
?>

<div class="header-action">
    <h2 class="page-title" style="margin-bottom:0;">Laporan Pelayanan Masyarakat</h2>
    <button class="btn" style="background:#10b981;" onclick="window.print()"><i class="fas fa-print"></i> Cetak Laporan</button>
</div>

<div class="card">
    <h3 style="margin-bottom: 20px;">Rekapitulasi Surat Keterangan / Pelayanan</h3>
    <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th style="width:50px;">No</th>
                    <th>Jenis Pelayanan / Kategori Surat</th>
                    <th style="text-align:center;">Jumlah Surat Dikeluarkan</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $total_layanan = 0;
                if(mysqli_num_rows($query_layanan) > 0) {
                    while($row = mysqli_fetch_assoc($query_layanan)) {
                        echo "<tr>
                            <td>{$no}</td>
                            <td>".htmlspecialchars($row['kategori'])."</td>
                            <td style='text-align:center; font-weight:bold; font-size:16px;'>{$row['jumlah']}</td>
                        </tr>";
                        $total_layanan += $row['jumlah'];
                        $no++;
                    }
                    echo "<tr style='background:#f8fafc;'><td colspan='2' style='text-align:right; font-weight:bold;'>TOTAL:</td><td style='text-align:center; font-weight:bold; font-size:18px; color:#3b82f6;'>{$total_layanan}</td></tr>";
                } else {
                    echo "<tr><td colspan='3' style='text-align:center;'>Belum ada data pelayanan.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../footer.php'; ?>
