<?php
// Set zona waktu default ke Waktu Indonesia Barat (WIB)
date_default_timezone_set('Asia/Jakarta');

session_start();

define('BASE_URL', 'http://localhost:8000/');

// =========================================================================
// PENGATURAN DATABASE (Ubah bagian ini saat aplikasi di-upload ke Hosting)
// =========================================================================
$host = 'localhost'; // Di hosting (cPanel), biasanya tetap 'localhost'
$user = 'root';      // GANTI dengan Username Database di Hosting Anda
$pass = '';          // GANTI dengan Password Database di Hosting Anda
$dbname = 'arsip_digital'; // GANTI dengan Nama Database di Hosting Anda

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
?>
