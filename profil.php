<?php
include 'header.php';

// Ambil semua data user
$query = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");
?>

<div class="header-action">
    <h2 class="page-title" style="margin-bottom:0;">Daftar Profil Akun</h2>
</div>

<?php
if(isset($_SESSION['success'])) {
    echo '<div class="alert alert-success">'.$_SESSION['success'].'</div>';
    unset($_SESSION['success']);
}
if(isset($_SESSION['error'])) {
    echo '<div class="alert" style="background:#fee2e2; color:#991b1b;">'.$_SESSION['error'].'</div>';
    unset($_SESSION['error']);
}
?>

<div class="stats-grid">
    <?php if(mysqli_num_rows($query) > 0): ?>
        <?php while($row = mysqli_fetch_assoc($query)): ?>
            <?php 
                $foto_raw = !empty($row['foto']) ? 'uploads/profil/' . $row['foto'] : '';
                $foto_display = !empty($row['foto']) ? 'uploads/profil/' . htmlspecialchars($row['foto']) : '';
                $has_foto = !empty($foto_raw) && file_exists($foto_raw);
                
                // Cek apakah ini akun yang sedang login
                $is_current_user = ($row['id'] == $_SESSION['user_id']);
            ?>
            <div class="card" style="display:flex; align-items:center; padding: 20px; gap: 20px; position: relative;">
                <div style="flex-shrink: 0;">
                    <?php if($has_foto): ?>
                        <img src="<?= $foto_display ?>" alt="Profil" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid <?= $is_current_user ? '#3b82f6' : '#e5e7eb' ?>;">
                    <?php else: ?>
                        <div style="width: 80px; height: 80px; border-radius: 50%; background: #f3f4f6; display: flex; align-items: center; justify-content: center; border: 3px solid <?= $is_current_user ? '#3b82f6' : '#e5e7eb' ?>;">
                            <i class="fas fa-user" style="font-size: 35px; color: #9ca3af;"></i>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div style="flex-grow: 1;">
                    <h3 style="margin: 0 0 5px 0; color: #1f2937; font-size: 1.1rem;">
                        <?= htmlspecialchars($row['nama']) ?>
                        <?php if($is_current_user): ?>
                            <span style="font-size: 12px; color: #3b82f6; margin-left: 5px;">(Anda)</span>
                        <?php endif; ?>
                    </h3>
                    <p style="margin: 0 0 5px 0; color: #6b7280; font-size: 0.9rem;"><i class="fas fa-user-circle"></i> @<?= htmlspecialchars($row['username']) ?></p>
                    <?php 
                        if (isset($row['role'])) {
                            if ($row['role'] == 'sekretaris') {
                                $role_display = 'Sekretaris';
                                $role_color = '#fdf4ff';
                                $role_text = '#86198f';
                            } elseif ($row['role'] == 'lurah') {
                                $role_display = 'Lurah';
                                $role_color = '#f0fdf4';
                                $role_text = '#166534';
                            } else {
                                $role_display = 'Administrator';
                                $role_color = '#dbeafe';
                                $role_text = '#1e40af';
                            }
                        } else {
                            $role_display = 'Administrator';
                            $role_color = '#dbeafe';
                            $role_text = '#1e40af';
                        }
                    ?>
                    <span class="badge" style="background: <?= $role_color ?>; color: <?= $role_text ?>; font-size: 0.75rem;"><?= $role_display ?></span>
                    
                    <?php if($is_current_user): ?>
                    <div style="margin-top: 15px;">
                        <a href="edit_profil.php?id=<?= $row['id'] ?>" class="btn" style="padding: 4px 10px; font-size: 12px; background: #10b981;"><i class="fas fa-edit"></i> Edit</a>
                        <a href="hapus_profil.php?id=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus akun Anda sendiri? Anda akan otomatis logout setelah ini.');" class="btn" style="padding: 4px 10px; font-size: 12px; background: #ef4444;"><i class="fas fa-trash"></i> Hapus</a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="card" style="grid-column: 1 / -1; text-align: center; padding: 40px;">
            <p style="color: #6b7280;">Belum ada akun terdaftar.</p>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
