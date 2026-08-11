<?php
require_once '../config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Get file info to delete the physical file
    $query = mysqli_query($conn, "SELECT file_surat FROM surat_keluar WHERE id = $id");
    if (mysqli_num_rows($query) > 0) {
        $data = mysqli_fetch_assoc($query);
        if (!empty($data['file_surat'])) {
            $file_path = '../uploads/surat_keluar/' . $data['file_surat'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
        
        // Delete from database
        if (mysqli_query($conn, "DELETE FROM surat_keluar WHERE id = $id")) {
            $_SESSION['success'] = "Data surat keluar beserta filenya berhasil dihapus!";
        } else {
            $_SESSION['error'] = "Gagal menghapus data dari database.";
        }
    }
}

header("Location: penyimpanan_arsip_admin.php?tipe=keluar");
exit();
?>
