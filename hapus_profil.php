<?php
require_once 'config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "Akses ditolak!";
    header("Location: profil.php");
    exit();
}

$id = intval($_GET['id']);

// Keamanan: Cuma bisa hapus diri sendiri
if ($id !== intval($_SESSION['user_id'])) {
    $_SESSION['error'] = "Anda tidak memiliki izin untuk menghapus akun orang lain!";
    header("Location: profil.php");
    exit();
}

// Get file info to delete physical photo
$query = mysqli_query($conn, "SELECT foto FROM users WHERE id = $id");
if (mysqli_num_rows($query) > 0) {
    $data = mysqli_fetch_assoc($query);
    if (!empty($data['foto'])) {
        $file_path = 'uploads/profil/' . $data['foto'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }
    
    // Delete from database
    if (mysqli_query($conn, "DELETE FROM users WHERE id = $id")) {
        // Logout otomatis karena hapus diri sendiri
        session_destroy();
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['error'] = "Gagal menghapus akun.";
    }
}

header("Location: profil.php");
exit();
?>
