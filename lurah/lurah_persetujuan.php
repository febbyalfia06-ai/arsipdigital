<?php
require_once '../config.php';
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'lurah') {
    header("Location: ../index.php");
    exit();
}
include '../header.php';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_surat = mysqli_real_escape_string($conn, $_POST['id_surat']);
    $tipe_arsip = $_POST['tipe_arsip'];
    $action = $_POST['action'];

    if($action == 'setuju') {
        if($tipe_arsip == 'masuk') {
            $q = "UPDATE surat_masuk SET status = 'Disetujui Lurah' WHERE id = '$id_surat'";
        } else {
            $q = "UPDATE surat_keluar SET status = 'Disetujui Lurah' WHERE id = '$id_surat'";
        }
        
        if(mysqli_query($conn, $q)) {
            $_SESSION['success'] = "Arsip berhasil disetujui.";
        }
    } else if($action == 'tolak') {
        if($tipe_arsip == 'masuk') {
            $q = "UPDATE surat_masuk SET status = 'Ditolak Lurah' WHERE id = '$id_surat'";
        } else {
            $q = "UPDATE surat_keluar SET status = 'Ditolak Lurah' WHERE id = '$id_surat'";
        }
        
        if(mysqli_query($conn, $q)) {
            $_SESSION['success'] = "Arsip ditolak.";
        }
    }
    echo "<script>window.location.href='lurah_persetujuan.php';</script>";
    exit();
}

?>

<div class="header-action">
    <h2 class="page-title" style="margin-bottom:0;">Persetujuan Akhir Dokumen</h2>
</div>

<?php
if(isset($_SESSION['success'])) {
    echo '<div class="alert alert-success">'.$_SESSION['success'].'</div>';
    unset($_SESSION['success']);
}
?>

<div class="card" style="margin-bottom: 20px;">
    <h3 style="margin-bottom: 15px; font-size: 16px;">Arsip Masuk (Menunggu Persetujuan)</h3>
    <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th style="width:50px;">No</th>
                    <th>Nomor Surat</th>
                    <th>Nama Dokumen</th>
                    <th>Status</th>
                    <th>File</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $query = mysqli_query($conn, "SELECT * FROM surat_masuk WHERE status = 'Dikirim ke Lurah' ORDER BY id DESC");
                if(mysqli_num_rows($query) > 0) {
                    while($row = mysqli_fetch_assoc($query)) {
                        $secure_file_url = "../buka_file.php?folder=surat_masuk&file=" . urlencode($row['file_surat']);
                        echo "<tr>
                            <td>{$no}</td>
                            <td>".htmlspecialchars($row['nomor_surat'])."</td>
                            <td>".htmlspecialchars($row['perihal'])."</td>
                            <td><span class='badge' style='background:#fef3c7; color:#92400e;'>".htmlspecialchars($row['status'])."</span></td>
                            <td><a href='{$secure_file_url}' target='_blank' style='color:#3b82f6;'><i class='fas fa-file-alt'></i> Lihat</a></td>
                            <td style='text-align:center;'>
                                <form method='POST' style='display:inline-block;'>
                                    <input type='hidden' name='id_surat' value='{$row['id']}'>
                                    <input type='hidden' name='tipe_arsip' value='masuk'>
                                    <input type='hidden' name='action' value='setuju'>
                                    <button type='submit' class='btn' style='background:#10b981; padding:6px 12px; font-size:13px;' onclick=\"return confirm('Setujui dokumen ini?');\"><i class='fas fa-check'></i> Setujui</button>
                                </form>
                                <form method='POST' style='display:inline-block;'>
                                    <input type='hidden' name='id_surat' value='{$row['id']}'>
                                    <input type='hidden' name='tipe_arsip' value='masuk'>
                                    <input type='hidden' name='action' value='tolak'>
                                    <button type='submit' class='btn' style='background:#ef4444; padding:6px 12px; font-size:13px;' onclick=\"return confirm('Tolak dokumen ini?');\"><i class='fas fa-times'></i> Tolak</button>
                                </form>
                            </td>
                        </tr>";
                        $no++;
                    }
                } else {
                    echo "<tr><td colspan='6' style='text-align:center;'>Tidak ada arsip masuk yang perlu disetujui.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <h3 style="margin-bottom: 15px; font-size: 16px;">Arsip Keluar (Menunggu Persetujuan)</h3>
    <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th style="width:50px;">No</th>
                    <th>Nomor Surat</th>
                    <th>Nama Dokumen</th>
                    <th>Status</th>
                    <th>File</th>
                    <th style="text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $query = mysqli_query($conn, "SELECT * FROM surat_keluar WHERE status = 'Dikirim ke Lurah' ORDER BY id DESC");
                if(mysqli_num_rows($query) > 0) {
                    while($row = mysqli_fetch_assoc($query)) {
                        $secure_file_url = "../buka_file.php?folder=surat_keluar&file=" . urlencode($row['file_surat']);
                        echo "<tr>
                            <td>{$no}</td>
                            <td>".htmlspecialchars($row['nomor_surat'])."</td>
                            <td>".htmlspecialchars($row['perihal'])."</td>
                            <td><span class='badge' style='background:#fef3c7; color:#92400e;'>".htmlspecialchars($row['status'])."</span></td>
                            <td><a href='{$secure_file_url}' target='_blank' style='color:#3b82f6;'><i class='fas fa-file-alt'></i> Lihat</a></td>
                            <td style='text-align:center;'>
                                <form method='POST' style='display:inline-block;'>
                                    <input type='hidden' name='id_surat' value='{$row['id']}'>
                                    <input type='hidden' name='tipe_arsip' value='keluar'>
                                    <input type='hidden' name='action' value='setuju'>
                                    <button type='submit' class='btn' style='background:#10b981; padding:6px 12px; font-size:13px;' onclick=\"return confirm('Setujui dokumen ini?');\"><i class='fas fa-check'></i> Setujui</button>
                                </form>
                                <form method='POST' style='display:inline-block;'>
                                    <input type='hidden' name='id_surat' value='{$row['id']}'>
                                    <input type='hidden' name='tipe_arsip' value='keluar'>
                                    <input type='hidden' name='action' value='tolak'>
                                    <button type='submit' class='btn' style='background:#ef4444; padding:6px 12px; font-size:13px;' onclick=\"return confirm('Tolak dokumen ini?');\"><i class='fas fa-times'></i> Tolak</button>
                                </form>
                            </td>
                        </tr>";
                        $no++;
                    }
                } else {
                    echo "<tr><td colspan='6' style='text-align:center;'>Tidak ada arsip keluar yang perlu disetujui.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../footer.php'; ?>
