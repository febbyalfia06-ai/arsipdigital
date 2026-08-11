<?php
require_once '../config.php';
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}
include '../header.php';

if (!isset($_GET['id'])) {
    echo '<script>window.location.href="penyimpanan_arsip_admin.php?tipe=keluar";</script>';
    exit();
}

$id = intval($_GET['id']);
$q_data = mysqli_query($conn, "SELECT * FROM surat_keluar WHERE id = $id");
if (mysqli_num_rows($q_data) == 0) {
    echo '<script>window.location.href="penyimpanan_arsip_admin.php?tipe=keluar";</script>';
    exit();
}
$data = mysqli_fetch_assoc($q_data);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nomor_surat = mysqli_real_escape_string($conn, $_POST['nomor_surat']);
    $tanggal_surat = mysqli_real_escape_string($conn, $_POST['tanggal_surat']);
    $tujuan = mysqli_real_escape_string($conn, $_POST['tujuan']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $perihal = mysqli_real_escape_string($conn, $_POST['perihal']);
    
    $file_surat = $data['file_surat']; // keep old file by default
    
    // Proses Upload File jika ada file baru
    if (isset($_FILES['file_surat']) && $_FILES['file_surat']['error'] == 0) {
        $file_tmp = $_FILES['file_surat']['tmp_name'];
        $file_ext = strtolower(pathinfo($_FILES['file_surat']['name'], PATHINFO_EXTENSION));
        
        // VALIDASI BACKEND (Mencegah Hacker Upload File PHP/Virus)
        $allowed_ext = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png', 'zip', 'rar'];
        
        if (!in_array($file_ext, $allowed_ext)) {
            $error = "KEAMANAN: Ekstensi .$file_ext ditolak! File tidak aman.";
        } else {
            $file_name = time() . '_' . $_FILES['file_surat']['name'];
            $upload_dir = '../uploads/surat_keluar/';
            if (move_uploaded_file($file_tmp, $upload_dir . $file_name)) {
                // Hapus file lama jika ada
                if (!empty($data['file_surat']) && file_exists($upload_dir . $data['file_surat'])) {
                    unlink($upload_dir . $data['file_surat']);
                }
                $file_surat = $file_name;
            }
        }
    }

    if (!isset($error)) {
        $q = "UPDATE surat_keluar SET 
                nomor_surat = '$nomor_surat', 
                tanggal_surat = '$tanggal_surat', 
                tujuan = '$tujuan', 
                kategori = '$kategori', 
                perihal = '$perihal', 
                file_surat = '$file_surat' 
              WHERE id = $id";
        
        if (mysqli_query($conn, $q)) {
            $_SESSION['success'] = "Data surat keluar berhasil diupdate!";
            echo '<script>window.location.href="penyimpanan_arsip_admin.php?tipe=keluar";</script>';
            exit();
        } else {
            $error = "Gagal mengupdate data: " . mysqli_error($conn);
        }
    }
}
?>

<div class="header-action">
    <h2 class="page-title" style="margin-bottom:0;">Edit Surat Keluar</h2>
    <a href="penyimpanan_arsip_admin.php?tipe=keluar" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div class="card">
    <?php if(isset($error)) echo "<div class='alert' style='background:#fee2e2; color:#991b1b;'>$error</div>"; ?>
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Nomor Surat</label>
            <input type="text" name="nomor_surat" class="form-control" value="<?= htmlspecialchars($data['nomor_surat']) ?>" required>
        </div>
        <div class="form-group">
            <label>Tanggal Surat</label>
            <input type="date" name="tanggal_surat" class="form-control" value="<?= htmlspecialchars($data['tanggal_surat']) ?>" required>
        </div>
        <div class="form-group">
            <label>Tujuan / Penerima</label>
            <input type="text" name="tujuan" class="form-control" value="<?= htmlspecialchars($data['tujuan']) ?>" required>
        </div>
        <div class="form-group">
            <label>Kategori Surat Keluar</label>
            <select name="kategori" class="form-control" required>
                <option value="Surat keterangan domisili" <?= $data['kategori'] == 'Surat keterangan domisili' ? 'selected' : '' ?>>1. Surat keterangan domisili</option>
                <option value="Surat keterangan tidak mampu (SKTM)" <?= $data['kategori'] == 'Surat keterangan tidak mampu (SKTM)' ? 'selected' : '' ?>>2. Surat keterangan tidak mampu (SKTM)</option>
                <option value="Surat keterangan Usaha (SKU)" <?= $data['kategori'] == 'Surat keterangan Usaha (SKU)' ? 'selected' : '' ?>>3. Surat keterangan Usaha (SKU)</option>
                <option value="Surat pengantar akta kematian" <?= $data['kategori'] == 'Surat pengantar akta kematian' ? 'selected' : '' ?>>4. Surat pengantar akta kematian</option>
                <option value="Surat keterangan belum nikah" <?= $data['kategori'] == 'Surat keterangan belum nikah' ? 'selected' : '' ?>>5. Surat keterangan belum nikah</option>
                <option value="Surat keterangan kematian" <?= $data['kategori'] == 'Surat keterangan kematian' ? 'selected' : '' ?>>6. Surat keterangan kematian</option>
                <option value="Surat pengantar nikah N1 (Islam)" <?= $data['kategori'] == 'Surat pengantar nikah N1 (Islam)' ? 'selected' : '' ?>>7. Surat pengantar nikah N1 (Islam)</option>
                <option value="Surat pengantar nikah N2 (Hindu)" <?= $data['kategori'] == 'Surat pengantar nikah N2 (Hindu)' ? 'selected' : '' ?>>8. Surat pengantar nikah N2 (Hindu)</option>
                <option value="Surat pengantar nikah N3 (Kristen Protestan)" <?= $data['kategori'] == 'Surat pengantar nikah N3 (Kristen Protestan)' ? 'selected' : '' ?>>9. Surat pengantar nikah N3 (Kristen Protestan)</option>
                <option value="Surat pengantar nikah N4 (Kristen)" <?= $data['kategori'] == 'Surat pengantar nikah N4 (Kristen)' ? 'selected' : '' ?>>10. Surat pengantar nikah N4 (Kristen)</option>
                <option value="Surat undangan" <?= $data['kategori'] == 'Surat undangan' ? 'selected' : '' ?>>11. Surat undangan</option>
                <option value="Surat keterangan biasa" <?= $data['kategori'] == 'Surat keterangan biasa' ? 'selected' : '' ?>>12. Surat keterangan biasa</option>
                <option value="Perwalian nikah" <?= $data['kategori'] == 'Perwalian nikah' ? 'selected' : '' ?>>13. Perwalian nikah</option>
                <option value="Surat keterangan nama" <?= $data['kategori'] == 'Surat keterangan nama' ? 'selected' : '' ?>>14. Surat keterangan nama</option>
                <option value="Surat tugas" <?= $data['kategori'] == 'Surat tugas' ? 'selected' : '' ?>>15. Surat tugas</option>
            </select>
        </div>
        <div class="form-group">
            <label>Perihal / Nama Dokumen</label>
            <textarea name="perihal" class="form-control" rows="4" required><?= htmlspecialchars($data['perihal']) ?></textarea>
        </div>
        
        <div class="form-group">
            <label>File Saat Ini</label>
            <div>
                <?php if(!empty($data['file_surat']) && file_exists('../uploads/surat_keluar/' . $data['file_surat'])): ?>
                    <a href="../uploads/surat_keluar/<?= $data['file_surat'] ?>" target="_blank" class="btn" style="background:#3b82f6;"><i class="fas fa-eye"></i> Lihat File Saat Ini</a>
                <?php else: ?>
                    <span class="badge" style="background:#f3f4f6; color:#4b5563;">Tidak ada file</span>
                <?php endif; ?>
            </div>
        </div>

        <div class="form-group">
            <label>Ganti File (Kosongkan jika tidak ingin mengganti)</label>
            <input type="file" name="file_surat" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.zip,.rar">
        </div>
        
        <button type="submit" class="btn"><i class="fas fa-save"></i> Update Data</button>
    </form>
</div>

<?php include '../footer.php'; ?>
