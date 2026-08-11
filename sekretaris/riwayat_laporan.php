<?php
require_once '../config.php';
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'sekretaris') {
    header("Location: ../index.php");
    exit();
}
include '../header.php';
?>

<div class="header-action">
    <h2 class="page-title" style="margin-bottom:0;">Riwayat Laporan</h2>
</div>

<div class="card">
    <p style="margin-bottom: 15px; color: #666;">Daftar arsip yang dikembalikan (Ditolak) beserta catatannya.</p>
    <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th style="width:50px;">No</th>
                    <th>Nomor Surat</th>
                    <th>Tipe</th>
                    <th>Catatan Validasi / Penolakan</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $query = mysqli_query($conn, "
                    SELECT nomor_surat, catatan_validasi, 'Arsip Masuk' as tipe, id FROM surat_masuk WHERE status = 'Dikembalikan'
                    UNION 
                    SELECT nomor_surat, catatan_validasi, 'Arsip Keluar' as tipe, id FROM surat_keluar WHERE status = 'Dikembalikan'
                    ORDER BY id DESC
                ");
                if(mysqli_num_rows($query) > 0) {
                    while($row = mysqli_fetch_assoc($query)) {
                        echo "<tr>
                            <td>{$no}</td>
                            <td>".htmlspecialchars($row['nomor_surat'])."</td>
                            <td><span class='badge' style='background:#f3f4f6; color:#4b5563;'>".htmlspecialchars($row['tipe'])."</span></td>
                            <td style='color:#ef4444;'>".htmlspecialchars($row['catatan_validasi'])."</td>
                        </tr>";
                        $no++;
                    }
                } else {
                    echo "<tr><td colspan='4' style='text-align:center;'>Belum ada riwayat arsip dikembalikan.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../footer.php'; ?>
