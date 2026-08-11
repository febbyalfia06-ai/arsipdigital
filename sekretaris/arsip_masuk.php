<?php
require_once '../config.php';
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'sekretaris') {
    header("Location: ../index.php");
    exit();
}
include '../header.php';

// Ini adalah halaman read-only untuk sekretaris melihat arsip masuk
$query = mysqli_query($conn, "SELECT * FROM surat_masuk ORDER BY id DESC");
?>

<div class="header-action">
    <h2 class="page-title" style="margin-bottom:0;">Data Arsip Masuk</h2>
</div>

<div class="card">
    <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th style="width:50px;">No</th>
                    <th>Nomor Surat</th>
                    <th>Nama Dokumen</th>
                    <th>Kategori</th>
                    <th>Status</th>
                    <th>File</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                if(mysqli_num_rows($query) > 0) {
                    while($row = mysqli_fetch_assoc($query)) {
                        $file_path = "../uploads/surat_masuk/" . $row['file_surat'];
                        $file_exists = !empty($row['file_surat']) && file_exists($file_path);
                        $secure_file_url = "../buka_file.php?folder=surat_masuk&file=" . urlencode($row['file_surat']);
                        
                        $status_color = '#6b7280'; // default gray
                        if($row['status'] == 'Divalidasi') $status_color = '#10b981';
                        else if($row['status'] == 'Dikembalikan') $status_color = '#ef4444';
                        else if($row['status'] == 'Dikirim ke Lurah') $status_color = '#3b82f6';
                        
                        echo "<tr>
                            <td>{$no}</td>
                            <td>".htmlspecialchars($row['nomor_surat'])."</td>
                            <td>".htmlspecialchars($row['perihal'])."</td>
                            <td>".htmlspecialchars($row['kategori'])."</td>
                            <td><span style='color:{$status_color}; font-weight:500;'>".htmlspecialchars($row['status'])."</span></td>
                            <td>";
                        if($file_exists) {
                            echo "<a href='{$secure_file_url}' target='_blank' style='color:#3b82f6; text-decoration:none;'><i class='fas fa-download'></i> Download</a>";
                        } else {
                            echo "<span style='color:#ef4444;'>Tidak ada</span>";
                        }
                        echo "</td>
                        </tr>";
                        $no++;
                    }
                } else {
                    echo "<tr><td colspan='6' style='text-align:center;'>Belum ada arsip masuk.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../footer.php'; ?>
