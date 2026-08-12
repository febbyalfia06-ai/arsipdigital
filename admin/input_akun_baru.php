<?php
require_once '../config.php';
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}
include '../header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = md5($_POST['password']);
    $role = isset($_POST['role']) ? mysqli_real_escape_string($conn, $_POST['role']) : 'admin';
    
    // Cek duplikasi username
    $check_user = mysqli_query($conn, "SELECT id FROM users WHERE username = '$username'");
    if (mysqli_num_rows($check_user) > 0) {
        $error = "Username '$username' sudah digunakan! Silakan gunakan username lain.";
    }

    $foto = '';
    
    // Proses Upload Foto Profil
    if (!isset($error) && isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $file_tmp = $_FILES['foto']['tmp_name'];
        $file_ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (!in_array($file_ext, $allowed_ext)) {
            $error = "KEAMANAN: Format gambar tidak valid. Hanya menerima JPG, PNG, GIF.";
        } else {
            $file_name = time() . '_' . $_FILES['foto']['name'];
            $upload_dir = '../uploads/profil/';
            if (move_uploaded_file($file_tmp, $upload_dir . $file_name)) {
                $foto = $file_name;
            }
        }
    }

    if (!isset($error)) {
        $q = "INSERT INTO users (nama, username, password, foto, role) 
              VALUES ('$nama', '$username', '$password', '$foto', '$role')";
        
        if (mysqli_query($conn, $q)) {
            $_SESSION['success'] = "Akun baru berhasil ditambahkan!";
            echo '<script>window.location.href="../profil.php";</script>';
            exit();
        } else {
            $error = "Gagal menyimpan data: " . mysqli_error($conn);
        }
    }
}
?>

<div class="header-action">
    <h2 class="page-title" style="margin-bottom:0;">Input Akun Baru</h2>
</div>

<div class="card">
    <?php if(isset($error)) echo "<div class='alert' style='background:#fee2e2; color:#991b1b;'>$error</div>"; ?>
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-grid">
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" placeholder="Masukkan nama lengkap" required>
            </div>
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control" placeholder="Masukkan username untuk login" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
            </div>
            <div class="form-group">
                <label>Hak Akses / Role</label>
                <select name="role" class="form-control" required>
                    <option value="admin">Administrator</option>
                    <option value="sekretaris">Sekretaris</option>
                    <option value="lurah">Lurah</option>
                </select>
            </div>
            <div class="form-group col-span-full">
                <label>Upload Foto Profil</label>
                <input type="file" name="foto" class="form-control" accept="image/*">
                <small style="color:#6b7280; display:block; margin-top:5px;">Biarkan kosong jika tidak ingin menambahkan foto.</small>
            </div>
        </div>
        <button type="submit" class="btn" style="background:#3b82f6; width: 100%; margin-top: 10px;"><i class="fas fa-save"></i> Simpan Akun</button>
    </form>
</div>

<?php include '../footer.php'; ?>
