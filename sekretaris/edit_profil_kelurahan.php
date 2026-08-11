<?php
require_once '../config.php';
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'sekretaris') {
    header("Location: ../index.php");
    exit();
}
include '../header.php';

// Ambil data profil saat ini
$q = mysqli_query($conn, "SELECT * FROM profil_kelurahan WHERE id = 1");
$profil = mysqli_fetch_assoc($q);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $telepon = mysqli_real_escape_string($conn, $_POST['telepon']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $lurah = mysqli_real_escape_string($conn, $_POST['lurah']);
    $sekretaris = mysqli_real_escape_string($conn, $_POST['sekretaris']);
    $kepala_sekretariat = mysqli_real_escape_string($conn, $_POST['kepala_sekretariat']);
    $kasi_pemerintahan = mysqli_real_escape_string($conn, $_POST['kasi_pemerintahan']);
    $kasi_permasbang = mysqli_real_escape_string($conn, $_POST['kasi_permasbang']);
    $kasi_kesejahteraan = mysqli_real_escape_string($conn, $_POST['kasi_kesejahteraan']);

    $update = "UPDATE profil_kelurahan SET 
        alamat = '$alamat', 
        telepon = '$telepon', 
        email = '$email', 
        lurah = '$lurah', 
        sekretaris = '$sekretaris', 
        kepala_sekretariat = '$kepala_sekretariat', 
        kasi_pemerintahan = '$kasi_pemerintahan', 
        kasi_permasbang = '$kasi_permasbang', 
        kasi_kesejahteraan = '$kasi_kesejahteraan' 
        WHERE id = 1";
        
    if (mysqli_query($conn, $update)) {
        $_SESSION['success'] = "Profil Kelurahan berhasil diperbarui!";
        echo '<script>window.location.href="profil_kelurahan.php";</script>';
        exit();
    } else {
        $error = "Gagal memperbarui data: " . mysqli_error($conn);
    }
}
?>

<div class="header-action">
    <h2 class="page-title" style="margin-bottom:0;">Edit Profil Kelurahan</h2>
    <a href="profil_kelurahan.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div class="card">
    <?php if(isset($error)) echo "<div class='alert' style='background:#fee2e2; color:#991b1b;'>$error</div>"; ?>
    <form action="" method="POST">
        <div class="form-group">
            <label>Alamat</label>
            <textarea name="alamat" class="form-control" rows="3" required><?= htmlspecialchars($profil['alamat']) ?></textarea>
        </div>
        <div class="form-group">
            <label>Telepon</label>
            <input type="text" name="telepon" class="form-control" value="<?= htmlspecialchars($profil['telepon']) ?>" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($profil['email']) ?>" required>
        </div>
        <div class="form-group">
            <label>Lurah</label>
            <input type="text" name="lurah" class="form-control" value="<?= htmlspecialchars($profil['lurah']) ?>" required>
        </div>
        <div class="form-group">
            <label>Sekretaris Kelurahan</label>
            <input type="text" name="sekretaris" class="form-control" value="<?= htmlspecialchars($profil['sekretaris']) ?>" required>
        </div>
        <div class="form-group">
            <label>Kepala Sekretariat</label>
            <input type="text" name="kepala_sekretariat" class="form-control" value="<?= htmlspecialchars($profil['kepala_sekretariat']) ?>" required>
        </div>
        <div class="form-group">
            <label>Kepala Seksi Pemerintahan</label>
            <input type="text" name="kasi_pemerintahan" class="form-control" value="<?= htmlspecialchars($profil['kasi_pemerintahan']) ?>" required>
        </div>
        <div class="form-group">
            <label>Kepala Seksi Pemberdayaan Masyarakat</label>
            <input type="text" name="kasi_permasbang" class="form-control" value="<?= htmlspecialchars($profil['kasi_permasbang']) ?>" required>
        </div>
        <div class="form-group">
            <label>Kepala Seksi Kesejahteraan Sosial</label>
            <input type="text" name="kasi_kesejahteraan" class="form-control" value="<?= htmlspecialchars($profil['kasi_kesejahteraan']) ?>" required>
        </div>
        <button type="submit" class="btn"><i class="fas fa-save"></i> Simpan Perubahan</button>
    </form>
</div>

<?php include '../footer.php'; ?>
