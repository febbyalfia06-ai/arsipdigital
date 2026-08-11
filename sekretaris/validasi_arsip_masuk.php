<?php
require_once '../config.php';
// Cek akses hanya untuk sekretaris (opsional tapi disarankan)
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'sekretaris') {
    header("Location: ../index.php");
    exit();
}
include '../header.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_surat = mysqli_real_escape_string($conn, $_POST['id_surat']);
    $action = $_POST['action'];
    $catatan = isset($_POST['catatan']) ? mysqli_real_escape_string($conn, $_POST['catatan']) : '';

    if($action == 'approve') {
        $q = "UPDATE surat_masuk SET status = 'Divalidasi', catatan_validasi = '' WHERE id = '$id_surat'";
        if(mysqli_query($conn, $q)) {
            $_SESSION['success'] = "Arsip masuk berhasil divalidasi.";
        }
    } else if($action == 'reject') {
        $q = "UPDATE surat_masuk SET status = 'Dikembalikan', catatan_validasi = '$catatan' WHERE id = '$id_surat'";
        if(mysqli_query($conn, $q)) {
            $_SESSION['success'] = "Arsip masuk dikembalikan dengan catatan.";
        }
    }
    echo "<script>window.location.href='validasi_arsip_masuk.php';</script>";
    exit();
}

?>

<div class="header-action">
    <h2 class="page-title" style="margin-bottom:0;">Validasi Arsip Masuk</h2>
</div>

<?php
if(isset($_SESSION['success'])) {
    echo '<div class="alert alert-success">'.$_SESSION['success'].'</div>';
    unset($_SESSION['success']);
}
?>

<div class="card">
    <p style="margin-bottom: 15px; color: #666;">Daftar arsip masuk yang perlu diperiksa kelengkapannya sebelum divalidasi.</p>
    <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th style="width:50px;">No</th>
                    <th>Nomor Surat</th>
                    <th>Nama Dokumen</th>
                    <th>Tgl. Terima</th>
                    <th>File Dokumen</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $query = mysqli_query($conn, "SELECT * FROM surat_masuk WHERE status = 'Pending' OR status = '' OR status IS NULL ORDER BY id DESC");
                if(mysqli_num_rows($query) > 0) {
                    while($row = mysqli_fetch_assoc($query)) {
                        $file_path = "../uploads/surat_masuk/" . $row['file_surat'];
                        $file_exists = !empty($row['file_surat']) && file_exists($file_path);
                        $secure_file_url = "../buka_file.php?folder=surat_masuk&file=" . urlencode($row['file_surat']);
                        
                        echo "<tr>
                            <td>{$no}</td>
                            <td>".htmlspecialchars($row['nomor_surat'])."</td>
                            <td>".htmlspecialchars($row['perihal'])."</td>
                            <td>".date('d-m-Y', strtotime($row['tanggal_diterima']))."</td>
                            <td>";
                        if($file_exists) {
                            echo "<a href='{$secure_file_url}' target='_blank' style='color:#3b82f6; text-decoration:none;'><i class='fas fa-file-download'></i> Lihat File</a>";
                        } else {
                            echo "<span style='color:#ef4444;'>Tidak ada file</span>";
                        }
                        echo "</td>
                            <td style='text-align:center; min-width: 250px;'>
                                <form method='POST' style='display:inline-block; margin-right:5px;'>
                                    <input type='hidden' name='id_surat' value='{$row['id']}'>
                                    <input type='hidden' name='action' value='approve'>
                                    <button type='submit' class='btn' style='background:#10b981; padding:6px 12px; font-size:13px;' onclick=\"return confirm('Validasi dokumen ini?');\"><i class='fas fa-check'></i> Validasi</button>
                                </form>
                                <button type='button' class='btn' style='background:#f59e0b; padding:6px 12px; font-size:13px;' onclick=\"document.getElementById('reject-form-{$row['id']}').style.display='block'\"><i class='fas fa-undo'></i> Kembalikan</button>
                                
                                <div id='reject-form-{$row['id']}' style='display:none; margin-top:10px; background:#f9fafb; padding:10px; border:1px solid #e5e7eb; border-radius:4px; text-align:left;'>
                                    <form method='POST'>
                                        <input type='hidden' name='id_surat' value='{$row['id']}'>
                                        <input type='hidden' name='action' value='reject'>
                                        <textarea name='catatan' class='form-control' placeholder='Catatan pengembalian...' required style='margin-bottom:10px; font-size:13px;'></textarea>
                                        <div style='text-align:right;'>
                                            <button type='button' class='btn' style='background:#9ca3af; padding:4px 8px; font-size:12px;' onclick=\"document.getElementById('reject-form-{$row['id']}').style.display='none'\">Batal</button>
                                            <button type='submit' class='btn' style='background:#ef4444; padding:4px 8px; font-size:12px;'>Kirim</button>
                                        </div>
                                    </form>
                                </div>
                            </td>
                        </tr>";
                        $no++;
                    }
                } else {
                    echo "<tr><td colspan='6' style='text-align:center;'>Tidak ada arsip masuk yang perlu divalidasi.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../footer.php'; ?>
