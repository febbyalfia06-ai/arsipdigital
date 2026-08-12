<?php
require_once '../config.php';
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}
include '../header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nomor_surat = mysqli_real_escape_string($conn, $_POST['nomor_surat']);
    $tanggal_surat = mysqli_real_escape_string($conn, $_POST['tanggal_surat']);
    $tujuan = mysqli_real_escape_string($conn, $_POST['tujuan']);
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $perihal = mysqli_real_escape_string($conn, $_POST['perihal']);

    $file_surat = '';
    
    // Proses Upload File
    if (isset($_FILES['file_surat']) && $_FILES['file_surat']['error'] == 0) {
        $file_tmp = $_FILES['file_surat']['tmp_name'];
        $file_ext = strtolower(pathinfo($_FILES['file_surat']['name'], PATHINFO_EXTENSION));
        
        // VALIDASI BACKEND (Mencegah Hacker Upload File PHP/Virus)
        $allowed_ext = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png', 'zip', 'rar'];
        
        if (!in_array($file_ext, $allowed_ext)) {
            $error = "KEAMANAN: Ekstensi .$file_ext ditolak! Hacker terdeteksi.";
        } else {
            $file_name = time() . '_' . $_FILES['file_surat']['name'];
            $upload_dir = '../uploads/surat_keluar/';
            if (move_uploaded_file($file_tmp, $upload_dir . $file_name)) {
                $file_surat = $file_name;
            }
        }
    }

    // Jika tidak ada error validasi keamanan, baru simpan ke DB
    if (!isset($error)) {
        $q = "INSERT INTO surat_keluar (nomor_surat, tanggal_surat, tujuan, kategori, perihal, file_surat) 
              VALUES ('$nomor_surat', '$tanggal_surat', '$tujuan', '$kategori', '$perihal', '$file_surat')";
        
        if (mysqli_query($conn, $q)) {
            $_SESSION['success'] = "Data surat keluar berhasil ditambahkan!";
            echo '<script>window.location.href="dashboard_admin.php";</script>';
            exit();
        } else {
            $error = "Gagal menyimpan data: " . mysqli_error($conn);
        }
    }
}
?>

<div class="header-action">
    <h2 class="page-title" style="margin-bottom:0;">Input Arsip Keluar</h2>
</div>

<div class="card">
    <?php if(isset($error)) echo "<div class='alert' style='background:#fee2e2; color:#991b1b;'>$error</div>"; ?>
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-grid">
            <div class="form-group">
                <label>Nomor Surat</label>
                <input type="text" name="nomor_surat" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Tanggal Surat</label>
                <input type="date" name="tanggal_surat" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Tujuan / Penerima</label>
                <input type="text" name="tujuan" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Kategori Arsip Keluar</label>
                <select name="kategori" class="form-control" required>
                    <option value="">-- Pilih Kategori --</option>
                    <option value="Surat keterangan domisili">1. Surat keterangan domisili</option>
                    <option value="Surat keterangan tidak mampu (SKTM)">2. Surat keterangan tidak mampu (SKTM)</option>
                    <option value="Surat keterangan Usaha (SKU)">3. Surat keterangan Usaha (SKU)</option>
                    <option value="Surat pengantar akta kematian">4. Surat pengantar akta kematian</option>
                    <option value="Surat keterangan belum nikah">5. Surat keterangan belum nikah</option>
                    <option value="Surat keterangan kematian">6. Surat keterangan kematian</option>
                    <option value="Surat pengantar nikah N1 (Islam)">7. Surat pengantar nikah N1 (Islam)</option>
                    <option value="Surat pengantar nikah N2 (Hindu)">8. Surat pengantar nikah N2 (Hindu)</option>
                    <option value="Surat pengantar nikah N3 (Kristen Protestan)">9. Surat pengantar nikah N3 (Kristen Protestan)</option>
                    <option value="Surat pengantar nikah N4 (Kristen)">10. Surat pengantar nikah N4 (Kristen)</option>
                    <option value="Surat undangan">11. Surat undangan</option>
                    <option value="Surat keterangan biasa">12. Surat keterangan biasa</option>
                    <option value="Perwalian nikah">13. Perwalian nikah</option>
                    <option value="Surat keterangan nama">14. Surat keterangan nama</option>
                    <option value="Surat tugas">15. Surat tugas</option>
                </select>
            </div>
            <div class="form-group col-span-full">
                <label>Perihal / Nama Dokumen</label>
                <textarea name="perihal" class="form-control" rows="4" required></textarea>
            </div>
            <div class="form-group col-span-full">
                <label>Upload File (PDF/Word/Excel/Gambar)</label>
                <input type="file" name="file_surat" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.zip,.rar" required>
            </div>
        </div>
        <button type="submit" class="btn" style="width: 100%; margin-top: 10px;"><i class="fas fa-save"></i> Simpan Data</button>
    </form>
</div>

<?php include '../footer.php'; ?>
