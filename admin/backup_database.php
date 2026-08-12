<?php
require_once '../config.php';
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}
include '../header.php'; ?>
<div class="header-action">
    <h2 class="page-title" style="margin-bottom:0;">Setting: Backup Database</h2>
</div>
<div class="card">
    <p>Halaman ini akan digunakan untuk melakukan Backup dan Restore Database sistem.</p>
    <p class="text-muted">Fitur sedang dalam pengembangan...</p>
    <button class="btn" style="margin-top: 15px;"><i class="fas fa-download"></i> Backup Sekarang</button>
</div>
<?php include '../footer.php'; ?>
