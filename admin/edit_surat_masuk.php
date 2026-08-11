<?php
require_once '../config.php';
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}
include '../header.php';

if (!isset($_GET['id'])) {
    echo '<script>window.location.href="penyimpanan_arsip_admin.php";</script>';
    exit();
}

$id = intval($_GET['id']);
$q_data = mysqli_query($conn, "SELECT * FROM surat_masuk WHERE id = $id");
if (mysqli_num_rows($q_data) == 0) {
    echo '<script>window.location.href="penyimpanan_arsip_admin.php";</script>';
    exit();
}
$data = mysqli_fetch_assoc($q_data);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nomor_surat = mysqli_real_escape_string($conn, $_POST['nomor_surat']);
    $no_agenda = mysqli_real_escape_string($conn, $_POST['no_agenda']);
    $tanggal_surat = mysqli_real_escape_string($conn, $_POST['tanggal_surat']);
    $tanggal_diterima = mysqli_real_escape_string($conn, $_POST['tanggal_diterima']);
    $pengirim = mysqli_real_escape_string($conn, $_POST['pengirim']);
    
    // Process kepada
    $kepada_arr = isset($_POST['kepada_checkbox']) ? $_POST['kepada_checkbox'] : [];
    $kepada_manual = trim($_POST['kepada_manual']);
    if (!empty($kepada_manual)) {
        $kepada_arr[] = $kepada_manual;
    }
    $kepada = mysqli_real_escape_string($conn, implode(', ', $kepada_arr));

    $kategori = mysqli_real_escape_string($conn, $_POST['kategori']);
    $sifat = mysqli_real_escape_string($conn, $_POST['sifat']);
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
            $upload_dir = '../uploads/surat_masuk/';
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
        $q = "UPDATE surat_masuk SET 
                nomor_surat = '$nomor_surat', 
                no_agenda = '$no_agenda',
                tanggal_surat = '$tanggal_surat', 
                tanggal_diterima = '$tanggal_diterima',
                pengirim = '$pengirim',
                kepada = '$kepada',
                kategori = '$kategori', 
                sifat = '$sifat',
                perihal = '$perihal', 
                file_surat = '$file_surat' 
              WHERE id = $id";
        
        if (mysqli_query($conn, $q)) {
            $_SESSION['success'] = "Data surat masuk berhasil diupdate!";
            echo '<script>window.location.href="penyimpanan_arsip_admin.php";</script>';
            exit();
        } else {
            $error = "Gagal mengupdate data: " . mysqli_error($conn);
        }
    }
}
?>

<div class="header-action">
    <h2 class="page-title" style="margin-bottom:0;">Edit Surat Masuk</h2>
    <a href="penyimpanan_arsip_admin.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div class="card">
    <?php if(isset($error)) echo "<div class='alert' style='background:#fee2e2; color:#991b1b;'>$error</div>"; ?>
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Nomor Surat</label>
            <input type="text" name="nomor_surat" class="form-control" value="<?= htmlspecialchars($data['nomor_surat']) ?>" required>
        </div>
        <div class="form-group">
            <label>No Agenda</label>
            <input type="text" name="no_agenda" class="form-control" value="<?= htmlspecialchars($data['no_agenda'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label>Tanggal Surat</label>
            <input type="date" name="tanggal_surat" class="form-control" value="<?= htmlspecialchars($data['tanggal_surat']) ?>" required>
        </div>
        <div class="form-group">
            <label>Diterima Tanggal</label>
            <input type="date" name="tanggal_diterima" class="form-control" value="<?= htmlspecialchars($data['tanggal_diterima']) ?>" required>
        </div>
        <div class="form-group">
            <label>Surat Dari</label>
            <input type="text" name="pengirim" class="form-control" value="<?= htmlspecialchars($data['pengirim']) ?>" required>
        </div>
        <div class="form-group">
            <label>Kepada</label>
            <?php 
                $existing_kepada = explode(', ', $data['kepada'] ?? ''); 
                $checkbox_options = ['Sekretaris Kelurahan', 'Kasi PEMTRANTIBUM', 'Kasi Permasbang', 'Kasi Kesejahteraan'];
                
                // Cari mana yang bukan bagian dari checkbox standard (berarti itu input manual)
                $manual_values = [];
                foreach($existing_kepada as $item) {
                    if(!empty($item) && !in_array($item, $checkbox_options)) {
                        $manual_values[] = $item;
                    }
                }
                $manual_text = implode(', ', $manual_values);
            ?>
            <div style="display: flex; flex-direction: column; gap: 8px;">
                <?php foreach($checkbox_options as $opt): ?>
                    <label style="font-weight: normal; margin-bottom: 0;">
                        <input type="checkbox" name="kepada_checkbox[]" value="<?= $opt ?>" <?= in_array($opt, $existing_kepada) ? 'checked' : '' ?>> <?= $opt ?>
                    </label>
                <?php endforeach; ?>
                <input type="text" name="kepada_manual" class="form-control" placeholder="Lainnya (Input Manual)..." value="<?= htmlspecialchars($manual_text) ?>" style="margin-top: 5px;">
            </div>
        </div>
        <div class="form-group">
            <label>Kategori Surat Masuk</label>
            <select name="kategori" class="form-control" required>
                <option value="">-- Pilih Kategori --</option>
                <?php
                $kategori_list = [
                    "Surat dari kecamatan", "Surat dari pemerintah kota", "Surat dari instansi pemerintah lainnya",
                    "Surat dari RT/RW", "Surat permohonan dari masyarakat", "Surat undangan",
                    "Surat edaran",
                    "Surat pemberitahuan"
                ];
                foreach($kategori_list as $kat) {
                    $selected = ($data['kategori'] == $kat) ? 'selected' : '';
                    echo "<option value=\"$kat\" $selected>$kat</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label>Sifat Surat</label>
            <select name="sifat" class="form-control" required>
                <option value="">-- Pilih Sifat --</option>
                <option value="Sangat Segera" <?= (isset($data['sifat']) && $data['sifat'] == 'Sangat Segera') ? 'selected' : '' ?>>Sangat Segera</option>
                <option value="Segera" <?= (isset($data['sifat']) && $data['sifat'] == 'Segera') ? 'selected' : '' ?>>Segera</option>
                <option value="Rahasia" <?= (isset($data['sifat']) && $data['sifat'] == 'Rahasia') ? 'selected' : '' ?>>Rahasia</option>
            </select>
        </div>
        <div class="form-group">
            <label>Nama Dokumen / Perihal</label>
            <textarea name="perihal" class="form-control" rows="3" required><?= htmlspecialchars($data['perihal']) ?></textarea>
        </div>
        <div class="form-group">
            <label>Upload File Baru (Abaikan jika tidak ingin mengganti file)</label>
            <?php if(!empty($data['file_surat'])): ?>
                <div style="margin-bottom:10px; font-size:14px;">
                    File saat ini: <a href="../uploads/surat_masuk/<?= htmlspecialchars($data['file_surat']) ?>" target="_blank"><?= htmlspecialchars($data['file_surat']) ?></a>
                </div>
            <?php endif; ?>
            <input type="file" name="file_surat" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.zip,.rar">
        </div>
        <button type="submit" class="btn"><i class="fas fa-save"></i> Update Data</button>
    </form>
</div>

<?php include '../footer.php'; ?>
