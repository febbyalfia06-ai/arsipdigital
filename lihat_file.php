<?php
include 'header.php';

if (!isset($_GET['id'])) {
    echo '<script>history.back();</script>';
    exit();
}

$tipe_arsip = isset($_GET['tipe']) ? $_GET['tipe'] : 'masuk';
$table_name = ($tipe_arsip == 'keluar') ? 'surat_keluar' : 'surat_masuk';
$folder_name = ($tipe_arsip == 'keluar') ? 'surat_keluar' : 'surat_masuk';

$id = intval($_GET['id']);
$query = mysqli_query($conn, "SELECT * FROM $table_name WHERE id = $id");
if (mysqli_num_rows($query) == 0) {
    echo '<script>history.back();</script>';
    exit();
}

$row = mysqli_fetch_assoc($query);
$file_path = "uploads/{$folder_name}/" . $row['file_surat'];
$file_exists = !empty($row['file_surat']) && file_exists($file_path);
$secure_file_url = "buka_file.php?folder={$folder_name}&file=" . urlencode($row['file_surat']);
$ext = strtolower(pathinfo($row['file_surat'], PATHINFO_EXTENSION));
$is_image = in_array($ext, ['jpg', 'jpeg', 'png', 'gif']);
$is_pdf = ($ext == 'pdf');

$back_url = "javascript:history.back()";
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'admin') {
        $back_url = BASE_URL . "admin/penyimpanan_arsip_admin.php?tipe=" . $tipe_arsip;
    } elseif ($_SESSION['role'] == 'sekretaris') {
        $back_url = BASE_URL . "sekretaris/penyimpanan_arsip.php?tipe=" . $tipe_arsip;
    } elseif ($_SESSION['role'] == 'lurah') {
        $back_url = BASE_URL . "lurah/lurah_monitoring_arsip.php?tipe=" . $tipe_arsip;
    }
}
?>

<div class="header-action">
    <h2 class="page-title" style="margin-bottom:0;">Lihat Dokumen: <?= htmlspecialchars($row['perihal']) ?></h2>
    <a href="<?= $back_url ?>" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div class="card" style="text-align:center;">
    <?php if($file_exists): ?>
        <?php if($is_image): ?>
            <img src="<?= $secure_file_url ?>" alt="Preview" style="max-width:100%; height:auto; border:1px solid #ddd; border-radius:8px;">
        <?php elseif($is_pdf): ?>
            <iframe src="<?= $secure_file_url ?>" style="width:100%; height:80vh; border:none; border-radius:8px;"></iframe>
        <?php else: ?>
            <div style="padding: 50px; background: #f9fafb; border-radius: 8px;">
                <i class="fas fa-file fa-4x text-muted" style="margin-bottom:20px;"></i>
                <h3>File tidak dapat dipreview langsung</h3>
                <p>Silakan unduh file untuk melihat isinya.</p>
                <a href="<?= $secure_file_url ?>&download=1" download class="btn" style="background:#3b82f6;"><i class="fas fa-download"></i> Download File</a>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="alert" style="background:#fee2e2; color:#991b1b; text-align:left;">File dokumen tidak ditemukan di server.</div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
