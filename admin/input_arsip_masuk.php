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
    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $perihal = mysqli_real_escape_string($conn, $_POST['perihal']);

    $no_agenda = mysqli_real_escape_string($conn, $_POST['no_agenda']);
    $tanggal_diterima = mysqli_real_escape_string($conn, $_POST['tanggal_diterima']);
    $pengirim = mysqli_real_escape_string($conn, $_POST['pengirim']);
    
    // Process kepada
    $kepada_arr = isset($_POST['kepada_checkbox']) ? $_POST['kepada_checkbox'] : [];
    $kepada_manual = trim($_POST['kepada_manual']);
    if (!empty($kepada_manual)) {
        $kepada_arr[] = $kepada_manual;
    }
    $kepada = mysqli_real_escape_string($conn, implode(', ', $kepada_arr));

    $sifat = mysqli_real_escape_string($conn, $_POST['sifat']);

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
            $upload_dir = '../uploads/surat_masuk/';
            if (move_uploaded_file($file_tmp, $upload_dir . $file_name)) {
                $file_surat = $file_name;
            }
        }
    }

    // Jika tidak ada error validasi keamanan, baru simpan ke DB
    if (!isset($error)) {
        $q = "INSERT INTO surat_masuk (nomor_surat, no_agenda, tanggal_surat, tanggal_diterima, pengirim, kepada, kategori, sifat, perihal, file_surat) 
              VALUES ('$nomor_surat', '$no_agenda', '$tanggal_surat', '$tanggal_diterima', '$pengirim', '$kepada', '$kategori', '$sifat', '$perihal', '$file_surat')";
        
        if (mysqli_query($conn, $q)) {
            $_SESSION['success'] = "Data arsip berhasil ditambahkan!";
            echo '<script>window.location.href="dashboard_admin.php";</script>';
            exit();
        } else {
            $error = "Gagal menyimpan data: " . mysqli_error($conn);
        }
    }
}
?>

<div class="header-action">
    <h2 class="page-title" style="margin-bottom:0;">Input Arsip Masuk</h2>
</div>

<div class="card">
    <?php if(isset($error)) echo "<div class='alert' style='background:#fee2e2; color:#991b1b;'>$error</div>"; ?>
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Nomor Surat</label>
            <input type="text" name="nomor_surat" class="form-control" required>
        </div>
        <div class="form-group">
            <label>No Agenda</label>
            <input type="text" name="no_agenda" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Tanggal Surat</label>
            <input type="date" name="tanggal_surat" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Diterima Tanggal</label>
            <input type="date" name="tanggal_diterima" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Surat Dari</label>
            <input type="text" name="pengirim" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Kepada</label>
            <div style="display: flex; flex-direction: column; gap: 8px;">
                <label style="font-weight: normal; margin-bottom: 0;"><input type="checkbox" name="kepada_checkbox[]" value="Sekretaris Kelurahan"> Sekretaris Kelurahan</label>
                <label style="font-weight: normal; margin-bottom: 0;"><input type="checkbox" name="kepada_checkbox[]" value="Kasi PEMTRANTIBUM"> Kasi PEMTRANTIBUM</label>
                <label style="font-weight: normal; margin-bottom: 0;"><input type="checkbox" name="kepada_checkbox[]" value="Kasi Permasbang"> Kasi Permasbang</label>
                <label style="font-weight: normal; margin-bottom: 0;"><input type="checkbox" name="kepada_checkbox[]" value="Kasi Kesejahteraan"> Kasi Kesejahteraan</label>
                <input type="text" name="kepada_manual" class="form-control" placeholder="Lainnya ..." style="margin-top: 5px;">
            </div>
        </div>
        <div class="form-group">
            <label>Kategori Arsip Masuk</label>
            <select name="kategori" class="form-control" required>
                <option value="">-- Pilih Kategori --</option>
                <option value="Surat dari kecamatan">1. Surat dari kecamatan</option>
                <option value="Surat dari pemerintah kota">2. Surat dari pemerintah kota</option>
                <option value="Surat dari instansi pemerintah lainnya">3. Surat dari instansi pemerintah lainnya</option>
                <option value="Surat dari RT/RW">4. Surat dari RT/RW</option>
                <option value="Surat permohonan dari masyarakat">5. Surat permohonan dari masyarakat</option>
                <option value="Surat undangan">6. Surat undangan</option>
                <option value="Surat edaran">7. Surat edaran</option>
                <option value="Surat pemberitahuan">8. Surat pemberitahuan</option>
            </select>
        </div>
        <div class="form-group">
            <label>Sifat Surat</label>
            <select name="sifat" class="form-control" required>
                <option value="">-- Pilih Sifat --</option>
                <option value="Sangat Segera">Sangat Segera</option>
                <option value="Segera">Segera</option>
                <option value="Rahasia">Rahasia</option>
            </select>
        </div>
        <div class="form-group">
            <label>Nama Dokumen / Perihal</label>
            <textarea name="perihal" class="form-control" rows="3" required></textarea>
        </div>
        <div class="form-group">
            <label>Upload File (PDF/Word/Excel/Gambar)</label>
            <input type="file" name="file_surat" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.zip,.rar" required>
        </div>
        <button type="submit" class="btn"><i class="fas fa-save"></i> Simpan Data</button>
    </form>
</div>

<?php include '../footer.php'; ?>
