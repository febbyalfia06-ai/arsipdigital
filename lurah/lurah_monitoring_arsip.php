<?php
require_once '../config.php';
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'lurah') {
    header("Location: ../index.php");
    exit();
}
include '../header.php';

// Menentukan tipe arsip yang aktif (default: surat_masuk)
$tipe_arsip = isset($_GET['tipe']) ? $_GET['tipe'] : 'masuk';
$table_name = ($tipe_arsip == 'keluar') ? 'surat_keluar' : 'surat_masuk';

$search = isset($_GET['search']) ? $_GET['search'] : '';
?>

<div class="header-action">
    <h2 class="page-title" style="margin-bottom:0;">Monitoring Real-time Arsip</h2>
    <div>
        <form method="GET" action="" style="display:flex; gap:10px; align-items:center;">
            <input type="text" name="search" class="form-control" placeholder="Cari Nama Dokumen..." value="<?= htmlspecialchars($search) ?>" style="width:250px; padding: 8px 12px; border: 1px solid var(--border-color); border-radius: 4px;">
            <select name="tipe" class="form-control" onchange="this.form.submit()" style="width:200px;">
                <option value="masuk" <?= $tipe_arsip == 'masuk' ? 'selected' : '' ?>>📂 Arsip Masuk</option>
                <option value="keluar" <?= $tipe_arsip == 'keluar' ? 'selected' : '' ?>>📤 Arsip Keluar</option>
            </select>
            <button type="submit" class="btn" style="background:#3b82f6; color:white; padding:8px 15px;"><i class="fas fa-search"></i> Cari</button>
        </form>
    </div>
</div>

<div class="card">
    <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th style="width:50px;">No</th>
                    <th>Nomor Surat</th>
                    <th>Nama Dokumen</th>
                    <th>Kategori</th>
                    <th>Status Saat Ini</th>
                    <th>File</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $folder_name = ($tipe_arsip == 'keluar') ? 'surat_keluar' : 'surat_masuk';
                
                $where_sql = "";
                if (!empty($search)) {
                    $escaped_search = mysqli_real_escape_string($conn, $search);
                    $where_sql = "WHERE perihal LIKE '%$escaped_search%' OR nomor_surat LIKE '%$escaped_search%'";
                }
                
                $query = mysqli_query($conn, "SELECT * FROM $table_name $where_sql ORDER BY id DESC");
                if(mysqli_num_rows($query) > 0) {
                    while($row = mysqli_fetch_assoc($query)) {
                        $file_path = "../uploads/{$folder_name}/" . $row['file_surat'];
                        $file_exists = !empty($row['file_surat']) && file_exists($file_path);
                        $secure_file_url = "../buka_file.php?folder={$folder_name}&file=" . urlencode($row['file_surat']);
                        
                        $status_color = '#6b7280';
                        $status_text = empty($row['status']) ? 'Pending' : $row['status'];
                        if($status_text == 'Divalidasi') $status_color = '#3b82f6';
                        else if($status_text == 'Dikembalikan') $status_color = '#ef4444';
                        else if($status_text == 'Dikirim ke Lurah') $status_color = '#f59e0b';
                        else if($status_text == 'Disetujui Lurah') $status_color = '#10b981';
                        else if($status_text == 'Ditolak Lurah') $status_color = '#b91c1c';

                        echo "<tr>
                            <td>{$no}</td>
                            <td>".htmlspecialchars($row['nomor_surat'])."</td>
                            <td>".htmlspecialchars($row['perihal'])."</td>
                            <td>".htmlspecialchars($row['kategori'])."</td>
                            <td><span class='badge' style='background:transparent; border: 1px solid {$status_color}; color:{$status_color}; font-weight:bold;'>{$status_text}</span></td>
                            <td style='text-align:center;'>";
                        if($file_exists) {
                            echo "<a href='{$secure_file_url}' target='_blank' class='btn' style='padding:5px 10px; background:#3b82f6;' title='Lihat File'><i class='fas fa-file-alt'></i></a>";
                        } else {
                            echo "<span style='color:#ef4444; font-size:13px;'>Kosong</span>";
                        }
                        echo "</td>
                        </tr>";
                        $no++;
                    }
                } else {
                    echo "<tr><td colspan='6' style='text-align:center;'>Belum ada data dokumen</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../footer.php'; ?>
