<?php
require_once '../config.php';
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'sekretaris') {
    header("Location: ../index.php");
    exit();
}
include '../header.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_surat = mysqli_real_escape_string($conn, $_POST['id_surat']);
    $tipe_arsip = $_POST['tipe_arsip'];
    $action = $_POST['action'];

    if($action == 'kirim') {
        if($tipe_arsip == 'masuk') {
            $q = "UPDATE surat_masuk SET status = 'Dikirim ke Lurah' WHERE id = '$id_surat'";
        } else {
            $q = "UPDATE surat_keluar SET status = 'Dikirim ke Lurah' WHERE id = '$id_surat'";
        }
        
        if(mysqli_query($conn, $q)) {
            $_SESSION['success'] = "Arsip berhasil dikirim ke Lurah.";
        }
    }
    echo "<script>window.location.href='persetujuan_arsip.php';</script>";
    exit();
}

?>

<div class="header-action">
    <h2 class="page-title" style="margin-bottom:0;">Persetujuan Arsip (Kirim ke Lurah)</h2>
</div>

<?php
if(isset($_SESSION['success'])) {
    echo '<div class="alert alert-success">'.$_SESSION['success'].'</div>';
    unset($_SESSION['success']);
}
?>

<div class="card" style="margin-bottom: 20px;">
    <h3 style="margin-bottom: 15px; font-size: 16px;">Arsip Masuk (Divalidasi)</h3>
    <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th style="width:50px;">No</th>
                    <th>Nomor Surat</th>
                    <th>Nama Dokumen</th>
                    <th>Status</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $query = mysqli_query($conn, "SELECT id, nomor_surat, perihal, status FROM surat_masuk WHERE status = 'Divalidasi' ORDER BY id DESC");
                if(mysqli_num_rows($query) > 0) {
                    while($row = mysqli_fetch_assoc($query)) {
                        echo "<tr>
                            <td>{$no}</td>
                            <td>".htmlspecialchars($row['nomor_surat'])."</td>
                            <td>".htmlspecialchars($row['perihal'])."</td>
                            <td><span class='badge' style='background:#dbeafe; color:#1e40af;'>".htmlspecialchars($row['status'])."</span></td>
                            <td style='text-align:center;'>
                                <form method='POST'>
                                    <input type='hidden' name='id_surat' value='{$row['id']}'>
                                    <input type='hidden' name='tipe_arsip' value='masuk'>
                                    <input type='hidden' name='action' value='kirim'>
                                    <button type='submit' class='btn' style='background:#3b82f6; padding:6px 12px; font-size:13px;' onclick=\"return confirm('Kirim dokumen ini ke Lurah?');\"><i class='fas fa-paper-plane'></i> Kirim ke Lurah</button>
                                </form>
                            </td>
                        </tr>";
                        $no++;
                    }
                } else {
                    echo "<tr><td colspan='5' style='text-align:center;'>Tidak ada arsip masuk yang siap dikirim.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <h3 style="margin-bottom: 15px; font-size: 16px;">Arsip Keluar (Divalidasi)</h3>
    <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th style="width:50px;">No</th>
                    <th>Nomor Surat</th>
                    <th>Nama Dokumen</th>
                    <th>Status</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $query = mysqli_query($conn, "SELECT id, nomor_surat, perihal, status FROM surat_keluar WHERE status = 'Divalidasi' ORDER BY id DESC");
                if(mysqli_num_rows($query) > 0) {
                    while($row = mysqli_fetch_assoc($query)) {
                        echo "<tr>
                            <td>{$no}</td>
                            <td>".htmlspecialchars($row['nomor_surat'])."</td>
                            <td>".htmlspecialchars($row['perihal'])."</td>
                            <td><span class='badge' style='background:#dbeafe; color:#1e40af;'>".htmlspecialchars($row['status'])."</span></td>
                            <td style='text-align:center;'>
                                <form method='POST'>
                                    <input type='hidden' name='id_surat' value='{$row['id']}'>
                                    <input type='hidden' name='tipe_arsip' value='keluar'>
                                    <input type='hidden' name='action' value='kirim'>
                                    <button type='submit' class='btn' style='background:#3b82f6; padding:6px 12px; font-size:13px;' onclick=\"return confirm('Kirim dokumen ini ke Lurah?');\"><i class='fas fa-paper-plane'></i> Kirim ke Lurah</button>
                                </form>
                            </td>
                        </tr>";
                        $no++;
                    }
                } else {
                    echo "<tr><td colspan='5' style='text-align:center;'>Tidak ada arsip keluar yang siap dikirim.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../footer.php'; ?>
