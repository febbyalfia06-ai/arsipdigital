<?php
require_once '../config.php';
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'sekretaris') {
    header("Location: ../index.php");
    exit();
}
include '../header.php';

// Ini adalah halaman statis atau form master data (sesuai request: opsional)
?>
<?php
$q = mysqli_query($conn, "SELECT * FROM profil_kelurahan WHERE id = 1");
$profil = mysqli_fetch_assoc($q);
if (!$profil) {
    // Fallback if table is empty somehow
    $profil = [
        'alamat' => '-', 'telepon' => '-', 'email' => '-', 
        'lurah' => '-', 'sekretaris' => '-', 'kepala_sekretariat' => '-',
        'kasi_pemerintahan' => '-', 'kasi_permasbang' => '-', 'kasi_kesejahteraan' => '-'
    ];
}
?>

<div class="header-action">
    <h2 class="page-title" style="margin-bottom:0;">Profil Kelurahan</h2>
    <a href="edit_profil_kelurahan.php" class="btn"><i class="fas fa-edit"></i> Edit Profil Kelurahan</a>
</div>

<div class="card">
    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert" style="background:#dcfce7; color:#166534; padding:10px; border-radius:5px; margin-bottom:15px;">
            <?= $_SESSION['success']; unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    <div class="flex-col-mobile" style="display: flex; gap: 20px; align-items: flex-start;">
        <img src="../assets/logo_sidebar.png" alt="Logo Kelurahan" style="width: 150px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <div style="width: 100%;">
            <h3 style="margin-bottom: 15px; font-size: 20px; color: var(--text-main);">Kelurahan Pengasinan</h3>
            <div class="table-responsive">
                <table style="width: 100%; max-width: 600px; border: none;">
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 10px 0; font-weight: 500; width: 250px;">Alamat</td>
                    <td style="padding: 10px 0;"><?= htmlspecialchars($profil['alamat']) ?></td>
                </tr>
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 10px 0; font-weight: 500;">Telepon</td>
                    <td style="padding: 10px 0;"><?= htmlspecialchars($profil['telepon']) ?></td>
                </tr>
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 10px 0; font-weight: 500;">Email</td>
                    <td style="padding: 10px 0;"><?= htmlspecialchars($profil['email']) ?></td>
                </tr>
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 10px 0; font-weight: 500;">Lurah</td>
                    <td style="padding: 10px 0;"><?= htmlspecialchars($profil['lurah']) ?></td>
                </tr>
                 <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 10px 0; font-weight: 500;">Sekretaris Kelurahan</td>
                    <td style="padding: 10px 0;"><?= htmlspecialchars($profil['sekretaris']) ?></td>
                </tr>
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 10px 0; font-weight: 500;">Kepala Sekretariat</td>
                    <td style="padding: 10px 0;"><?= htmlspecialchars($profil['kepala_sekretariat']) ?></td>
                </tr> 
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 10px 0; font-weight: 500;">Kepala Seksi Pemerintahan</td>
                    <td style="padding: 10px 0;"><?= htmlspecialchars($profil['kasi_pemerintahan']) ?></td>
                </tr> 
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 10px 0; font-weight: 500;">Kepala Seksi Pemberdayaan Masyarakat</td>
                    <td style="padding: 10px 0;"><?= htmlspecialchars($profil['kasi_permasbang']) ?></td>
                </tr> 
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 10px 0; font-weight: 500;">Kepala Seksi Kesejahteraan Sosial</td>
                    <td style="padding: 10px 0;"><?= htmlspecialchars($profil['kasi_kesejahteraan']) ?></td>
                </tr> 

                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>
