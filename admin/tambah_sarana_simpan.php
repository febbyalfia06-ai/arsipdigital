<?php
include '../header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_sarana = mysqli_real_escape_string($conn, $_POST['nama_sarana']);
    $lokasi = mysqli_real_escape_string($conn, $_POST['lokasi']);
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);

    $q = "INSERT INTO sarana_simpan (nama_sarana, lokasi, keterangan) 
          VALUES ('$nama_sarana', '$lokasi', '$keterangan')";
    
    if (mysqli_query($conn, $q)) {
        $_SESSION['success'] = "Data penyimpanan arsip berhasil ditambahkan!";
        echo '<script>window.location.href="penyimpanan_arsip_admin.php";</script>';
        exit();
    } else {
        $error = "Gagal menyimpan data: " . mysqli_error($conn);
    }
}
?>

<div class="header-action">
    <h2 class="page-title" style="margin-bottom:0;">Tambah Penyimpanan Arsip</h2>
    <a href="penyimpanan_arsip_admin.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
</div>

<div class="card">
    <?php if(isset($error)) echo "<div class='alert' style='background:#fee2e2; color:#991b1b;'>$error</div>"; ?>
    <form action="" method="POST">
        <div class="form-group">
            <label>Nama Sarana (Misal: Lemari Arsip A)</label>
            <input type="text" name="nama_sarana" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Lokasi (Misal: Ruang Tata Usaha)</label>
            <input type="text" name="lokasi" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Keterangan Tambahan</label>
            <textarea name="keterangan" class="form-control" rows="4"></textarea>
        </div>
        <button type="submit" class="btn"><i class="fas fa-save"></i> Simpan Data</button>
    </form>
</div>

<?php include '../footer.php'; ?>
