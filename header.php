<?php
require_once __DIR__ . '/config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arsip Digital Kelurahan</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css?v=<?= time() ?>">
</head>
<body>

<div class="sidebar">
    <div class="sidebar-header" style="display:flex; align-items:center; gap:12px; padding:18px 20px;">
        <img src="<?= BASE_URL ?>assets/logo_sidebar.png" alt="Logo" style="width:65px; height:auto; flex-shrink:0; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.15)); border-radius: 8px;">
        <div>
            <h2 style="font-size:15px; margin:0; line-height:1.3;">Arsip Digital</h2>
            <p style="font-size:11px; margin:0; opacity:0.8; line-height:1.4;">Kelurahan Pengasinan</p>
        </div>
    </div>
    <ul class="nav-menu">
        <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'lurah'): ?>
            <!-- Menu Lurah -->
            <li><a href="<?= BASE_URL ?>lurah/dashboard_lurah.php" class="<?= basename($_SERVER['PHP_SELF']) == 'dashboard_lurah.php' ? 'active' : '' ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            
            <li class="nav-title">Persetujuan Dokumen</li>
            <li><a href="<?= BASE_URL ?>lurah/lurah_persetujuan.php" class="<?= basename($_SERVER['PHP_SELF']) == 'lurah_persetujuan.php' ? 'active' : '' ?>"><i class="fas fa-check-double"></i> Persetujuan Arsip</a></li>
            
            <li class="nav-title">Laporan</li>
            <li><a href="<?= BASE_URL ?>lurah/lurah_laporan_arsip.php" class="<?= basename($_SERVER['PHP_SELF']) == 'lurah_laporan_arsip.php' ? 'active' : '' ?>"><i class="fas fa-chart-pie"></i> Laporan Arsip</a></li>
            <li><a href="<?= BASE_URL ?>lurah/lurah_laporan_pelayanan.php" class="<?= basename($_SERVER['PHP_SELF']) == 'lurah_laporan_pelayanan.php' ? 'active' : '' ?>"><i class="fas fa-chart-line"></i> Laporan Pelayanan</a></li>
            <li><a href="<?= BASE_URL ?>lurah/lurah_statistik.php" class="<?= basename($_SERVER['PHP_SELF']) == 'lurah_statistik.php' ? 'active' : '' ?>"><i class="fas fa-chart-bar"></i> Statistik Arsip</a></li>

            <li class="nav-title">Monitoring</li>
            <li><a href="<?= BASE_URL ?>lurah/lurah_monitoring_kinerja.php" class="<?= basename($_SERVER['PHP_SELF']) == 'lurah_monitoring_kinerja.php' ? 'active' : '' ?>"><i class="fas fa-users-cog"></i> Monitoring Kinerja</a></li>
            <li><a href="<?= BASE_URL ?>lurah/lurah_monitoring_arsip.php" class="<?= basename($_SERVER['PHP_SELF']) == 'lurah_monitoring_arsip.php' ? 'active' : '' ?>"><i class="fas fa-folder-open"></i> Monitoring Arsip</a></li>
            
            <li class="nav-title">Akun</li>
            <li><a href="<?= BASE_URL ?>profil.php" class="<?= basename($_SERVER['PHP_SELF']) == 'profil.php' ? 'active' : '' ?>"><i class="fas fa-user-cog"></i> Ubah Profil</a></li>
            <li><a href="<?= BASE_URL ?>logout.php" class="text-danger"><i class="fas fa-sign-out-alt"></i> Logout</a></li>

        <?php elseif(isset($_SESSION['role']) && $_SESSION['role'] == 'sekretaris'): ?>
            <!-- Menu Sekretaris -->
            <li><a href="<?= BASE_URL ?>sekretaris/dashboard_sekretaris.php" class="<?= basename($_SERVER['PHP_SELF']) == 'dashboard_sekretaris.php' ? 'active' : '' ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            
            <li class="nav-title">Master Data</li>
            <li><a href="<?= BASE_URL ?>sekretaris/profil_kelurahan.php" class="<?= in_array(basename($_SERVER['PHP_SELF']), ['profil_kelurahan.php', 'edit_profil_kelurahan.php']) ? 'active' : '' ?>"><i class="fas fa-building"></i> Profil Kelurahan</a></li>
            <li><a href="<?= BASE_URL ?>sekretaris/data_administrasi.php" class="<?= basename($_SERVER['PHP_SELF']) == 'data_administrasi.php' ? 'active' : '' ?>"><i class="fas fa-file-alt"></i> Data Administrasi</a></li>
            
            <li class="nav-title">Validasi Dokumen</li>
            <li><a href="<?= BASE_URL ?>sekretaris/validasi_arsip_masuk.php" class="<?= basename($_SERVER['PHP_SELF']) == 'validasi_arsip_masuk.php' ? 'active' : '' ?>"><i class="fas fa-check-circle"></i> Validasi Arsip Masuk</a></li>
            <li><a href="<?= BASE_URL ?>sekretaris/validasi_arsip_keluar.php" class="<?= basename($_SERVER['PHP_SELF']) == 'validasi_arsip_keluar.php' ? 'active' : '' ?>"><i class="fas fa-check-square"></i> Validasi Arsip Keluar</a></li>
            <li><a href="<?= BASE_URL ?>sekretaris/persetujuan_arsip.php" class="<?= basename($_SERVER['PHP_SELF']) == 'persetujuan_arsip.php' ? 'active' : '' ?>"><i class="fas fa-stamp"></i> Persetujuan Arsip</a></li>
            
            <li class="nav-title">Pengelolaan Arsip</li>
            <li><a href="<?= BASE_URL ?>sekretaris/arsip_masuk.php" class="<?= basename($_SERVER['PHP_SELF']) == 'arsip_masuk.php' ? 'active' : '' ?>"><i class="fas fa-inbox"></i> Arsip Masuk</a></li>
            <li><a href="<?= BASE_URL ?>sekretaris/arsip_keluar.php" class="<?= basename($_SERVER['PHP_SELF']) == 'arsip_keluar.php' ? 'active' : '' ?>"><i class="fas fa-paper-plane"></i> Arsip Keluar</a></li>
            <li><a href="<?= BASE_URL ?>sekretaris/penyimpanan_arsip.php" class="<?= basename($_SERVER['PHP_SELF']) == 'penyimpanan_arsip.php' ? 'active' : '' ?>"><i class="fas fa-archive"></i> Penyimpanan Arsip</a></li>
            
            <li class="nav-title">Laporan</li>
            <li><a href="<?= BASE_URL ?>sekretaris/laporan_arsip.php" class="<?= basename($_SERVER['PHP_SELF']) == 'laporan_arsip.php' ? 'active' : '' ?>"><i class="fas fa-chart-pie"></i> Laporan Arsip</a></li>
            <li><a href="<?= BASE_URL ?>sekretaris/laporan_pelayanan.php" class="<?= basename($_SERVER['PHP_SELF']) == 'laporan_pelayanan.php' ? 'active' : '' ?>"><i class="fas fa-chart-line"></i> Laporan Pelayanan</a></li>
            <li><a href="<?= BASE_URL ?>sekretaris/riwayat_laporan.php" class="<?= basename($_SERVER['PHP_SELF']) == 'riwayat_laporan.php' ? 'active' : '' ?>"><i class="fas fa-history"></i> Riwayat Laporan</a></li>

            <li class="nav-title">Akun</li>
            <li><a href="<?= BASE_URL ?>profil.php" class="<?= basename($_SERVER['PHP_SELF']) == 'profil.php' ? 'active' : '' ?>"><i class="fas fa-user-cog"></i> Ubah Profil</a></li>
            <li><a href="<?= BASE_URL ?>logout.php" class="text-danger"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        <?php else: ?>
            <!-- Menu Admin -->
            <li><a href="<?= BASE_URL ?>admin/dashboard_admin.php" class="<?= basename($_SERVER['PHP_SELF']) == 'dashboard_admin.php' ? 'active' : '' ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            
            <li class="nav-title">Master Data</li>
            <li><a href="<?= BASE_URL ?>admin/input_akun_baru.php" class="<?= basename($_SERVER['PHP_SELF']) == 'input_akun_baru.php' ? 'active' : '' ?>"><i class="fas fa-user-plus"></i> Input Akun Baru</a></li>
            <li class="nav-title">Aplikasi Arsip</li>
            <li><a href="<?= BASE_URL ?>admin/input_arsip_masuk.php" class="<?= basename($_SERVER['PHP_SELF']) == 'input_arsip_masuk.php' ? 'active' : '' ?>"><i class="fas fa-file-import"></i> Input Arsip Masuk</a></li>
            <li><a href="<?= BASE_URL ?>admin/input_arsip_keluar.php" class="<?= basename($_SERVER['PHP_SELF']) == 'input_arsip_keluar.php' ? 'active' : '' ?>"><i class="fas fa-file-export"></i> Input Arsip Keluar</a></li>
            <li><a href="<?= BASE_URL ?>admin/penyimpanan_arsip_admin.php" class="<?= basename($_SERVER['PHP_SELF']) == 'penyimpanan_arsip_admin.php' ? 'active' : '' ?>"><i class="fas fa-box"></i> Penyimpanan Arsip</a></li>
            
            <li class="nav-title">Setting Aplikasi</li>
            <li><a href="<?= BASE_URL ?>admin/backup_database.php" class="<?= basename($_SERVER['PHP_SELF']) == 'backup_database.php' ? 'active' : '' ?>"><i class="fas fa-database"></i> Backup Database</a></li>

            <li class="nav-title">Akun</li>
            <li><a href="<?= BASE_URL ?>profil.php" class="<?= basename($_SERVER['PHP_SELF']) == 'profil.php' ? 'active' : '' ?>"><i class="fas fa-users"></i> Daftar Profil Akun</a></li>
            <li><a href="<?= BASE_URL ?>logout.php" class="text-danger"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        <?php endif; ?>
    </ul>
</div>

<!-- Sidebar Overlay -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="main-content">
    <div class="topbar">
        <div class="header-title-area">
            <button class="mobile-menu-toggle" id="mobileMenuBtn">
                <i class="fas fa-bars"></i>
            </button>
            <div>
                <?php 
                $current_page = basename($_SERVER['PHP_SELF']);
            $is_dashboard = in_array($current_page, ['dashboard_admin.php', 'dashboard_sekretaris.php', 'dashboard_lurah.php']);
            if(!$is_dashboard): 
            ?>
            <a href="javascript:history.back()" class="btn" style="background: #f1f5f9; color: #475569; padding: 8px 15px; font-size: 14px; text-decoration: none; border-radius: 6px; font-weight: 500; border: 1px solid #cbd5e1; box-shadow: 0 1px 2px rgba(0,0,0,0.05); transition: all 0.2s;">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <?php endif; ?>
        </div>
        <div class="user-info" style="display:flex; align-items:center; gap:10px; font-weight:500;">
            <i class="fas fa-user-circle fa-2x" style="color:var(--primary);"></i>
            <span>Halo, <?= htmlspecialchars($_SESSION['nama']) ?></span>
        </div>
    </div>
    <div class="container">
