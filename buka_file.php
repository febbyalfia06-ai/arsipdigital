<?php
require_once 'config.php';

// 1. CEK LOGIN (Keamanan Utama)
if (!isset($_SESSION['user_id'])) {
    header('HTTP/1.0 403 Forbidden');
    echo "Akses Ditolak. Anda harus login untuk melihat dokumen.";
    exit();
}

// 2. VALIDASI INPUT & MENCEGAH PATH TRAVERSAL
$folder_allowed = ['surat_masuk', 'surat_keluar'];
$folder = isset($_GET['folder']) ? $_GET['folder'] : '';
$file = isset($_GET['file']) ? $_GET['file'] : '';

if (!in_array($folder, $folder_allowed)) {
    header('HTTP/1.0 400 Bad Request');
    echo "Folder tidak valid.";
    exit();
}

// Gunakan basename() untuk menghilangkan potensi serangan ../../
$file = basename($file);
if (empty($file)) {
    header('HTTP/1.0 400 Bad Request');
    echo "Nama file tidak valid.";
    exit();
}

$file_path = __DIR__ . '/uploads/' . $folder . '/' . $file;

if (!file_exists($file_path)) {
    header('HTTP/1.0 404 Not Found');
    echo "File tidak ditemukan.";
    exit();
}

// 3. MENGATUR MIME TYPE UNTUK KEAMANAN EKSEKUSI BROWSER
$ext = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
$mime_type = 'application/octet-stream';

switch ($ext) {
    case 'pdf': $mime_type = 'application/pdf'; break;
    case 'jpg':
    case 'jpeg': $mime_type = 'image/jpeg'; break;
    case 'png': $mime_type = 'image/png'; break;
    case 'gif': $mime_type = 'image/gif'; break;
    // Untuk file doc/xls, biarkan browser mendownloadnya (octet-stream) demi keamanan agar tidak dieksekusi keliru
}

// 4. MENGELUARKAN (STREAMING) FILE KE BROWSER
header('Content-Description: File Transfer');
header('Content-Type: ' . $mime_type);
$disposition = (isset($_GET['download']) && $_GET['download'] == '1') ? 'attachment' : 'inline';
header('Content-Disposition: ' . $disposition . '; filename="' . $file . '"'); // 'inline' untuk preview, 'attachment' untuk download
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($file_path));

readfile($file_path);
exit();
?>
