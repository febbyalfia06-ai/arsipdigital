<?php
include 'header.php';

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "Akses ditolak!";
    echo '<script>window.location.href="profil.php";</script>';
    exit();
}

$id = intval($_GET['id']);

// Keamanan: Hanya bisa edit diri sendiri
if ($id !== intval($_SESSION['user_id'])) {
    $_SESSION['error'] = "Anda tidak memiliki izin untuk mengedit akun orang lain!";
    echo '<script>window.location.href="profil.php";</script>';
    exit();
}

$q_data = mysqli_query($conn, "SELECT * FROM users WHERE id = $id");
if (mysqli_num_rows($q_data) == 0) {
    $_SESSION['error'] = "Data tidak ditemukan!";
    echo '<script>window.location.href="profil.php";</script>';
    exit();
}
$data = mysqli_fetch_assoc($q_data);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    
    // Cek update password
    $password_query = "";
    if (!empty($_POST['password'])) {
        $password = md5($_POST['password']);
        $password_query = ", password = '$password'";
    }
    
    $foto = $data['foto'];
    
    // Proses Upload Foto Baru
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $file_tmp = $_FILES['foto']['tmp_name'];
        $file_ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (!in_array($file_ext, $allowed_ext)) {
            $error = "KEAMANAN: Format gambar tidak valid. Hanya menerima JPG, PNG, GIF.";
        } else {
            $file_name = time() . '_' . $_FILES['foto']['name'];
            $upload_dir = 'uploads/profil/';
            if (move_uploaded_file($file_tmp, $upload_dir . $file_name)) {
                // Hapus foto lama
                if (!empty($data['foto']) && file_exists($upload_dir . $data['foto'])) {
                    unlink($upload_dir . $data['foto']);
                }
                $foto = $file_name;
            }
        }
    }

    if (!isset($error)) {
        $q = "UPDATE users SET nama = '$nama', username = '$username', foto = '$foto' $password_query WHERE id = $id";
        
        if (mysqli_query($conn, $q)) {
            // Update session jika ganti nama
            $_SESSION['nama'] = $nama;
            $_SESSION['success'] = "Profil Anda berhasil diperbarui!";
            echo '<script>window.location.href="profil.php";</script>';
            exit();
        } else {
            $error = "Gagal menyimpan perubahan: " . mysqli_error($conn);
        }
    }
}
?>

<div class="header-action">
    <h2 class="page-title" style="margin-bottom:0;">Edit Profil Saya</h2>
    <a href="profil.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Batal</a>
</div>

<div class="card">
    <?php if(isset($error)) echo "<div class='alert' style='background:#fee2e2; color:#991b1b;'>$error</div>"; ?>
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Nama Lengkap</label>
            <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($data['nama']) ?>" required>
        </div>
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($data['username']) ?>" required>
        </div>
        <div class="form-group">
            <label>Ganti Password <span style="color:#6b7280; font-weight:normal;">(Kosongkan jika tidak ingin ganti)</span></label>
            <input type="password" name="password" class="form-control" placeholder="Masukkan password baru...">
        </div>
        
        <div class="form-group">
            <label>Foto Saat Ini</label>
            <div>
                <?php if(!empty($data['foto']) && file_exists('uploads/profil/' . $data['foto'])): ?>
                    <img src="uploads/profil/<?= $data['foto'] ?>" style="width:100px; height:100px; object-fit:cover; border-radius:50%; border:3px solid #e5e7eb;">
                <?php else: ?>
                    <span class="badge" style="background:#f3f4f6; color:#4b5563;">Tidak ada foto</span>
                <?php endif; ?>
            </div>
        </div>

        <div class="form-group">
            <label>Ganti Foto Profil <span style="color:#6b7280; font-weight:normal;">(Kosongkan jika tidak ingin ganti)</span></label>
            <input type="file" name="foto" class="form-control" accept="image/*">
        </div>
        
        <button type="submit" class="btn" style="background:#10b981;"><i class="fas fa-save"></i> Simpan Perubahan</button>
    </form>
</div>

<?php include 'footer.php'; ?>
