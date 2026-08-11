<?php
require_once '../config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Get file info to delete physical file
    $q_file = mysqli_query($conn, "SELECT file_surat FROM surat_masuk WHERE id = $id");
    if ($row = mysqli_fetch_assoc($q_file)) {
        if (!empty($row['file_surat'])) {
            $file_path = '../uploads/surat_masuk/' . $row['file_surat'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
    }
    
    // Delete record from database
    $q = "DELETE FROM surat_masuk WHERE id = $id";
    if (mysqli_query($conn, $q)) {
        $_SESSION['success'] = "Data surat masuk berhasil dihapus!";
    } else {
        $_SESSION['error'] = "Gagal menghapus data: " . mysqli_error($conn);
    }
}

header("Location: penyimpanan_arsip_admin.php");
exit();
?>
