<?php
require_once '../config.php';
if(!isset($_SESSION['role']) || $_SESSION['role'] != 'sekretaris') {
    header("Location: ../index.php");
    exit();
}
include '../header.php';

// Menentukan tipe arsip yang aktif (default: surat_masuk)
$tipe_arsip = isset($_GET['tipe']) ? $_GET['tipe'] : 'masuk';
$table_name = ($tipe_arsip == 'keluar') ? 'surat_keluar' : 'surat_masuk';

// Filter Kategori dan Pencarian
$filter_kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Mengambil list kategori dari tabel yang aktif
$kategori_list = [];
if ($tipe_arsip == 'keluar') {
    $kategori_list = [
        'Surat keterangan domisili',
        'Surat keterangan tidak mampu (SKTM)',
        'Surat keterangan Usaha (SKU)',
        'Surat pengantar akta kematian',
        'Surat keterangan belum nikah',
        'Surat keterangan kematian',
        'Surat pengantar nikah N1 (Islam)',
        'Surat pengantar nikah N2 (Hindu)',
        'Surat pengantar nikah N3 (Kristen Protestan)',
        'Surat pengantar nikah N4 (Kristen)',
        'Surat undangan',
        'Surat keterangan biasa',
        'Perwalian nikah',
        'Surat keterangan nama',
        'Surat tugas'
    ];
} else {
    $kategori_list = [
        'Surat dari kecamatan',
        'Surat dari pemerintah kota',
        'Surat dari instansi pemerintah lainnya',
        'Surat dari RT/RW',
        'Surat permohonan dari masyarakat',
        'Surat undangan',
        'Surat edaran',
        'Surat pemberitahuan'
    ];
}

// Helper function to format file size
function formatBytes($bytes, $precision = 2) { 
    if ($bytes == 0) return '0 B';
    $units = array('B', 'KB', 'MB', 'GB', 'TB'); 
    
    $bytes = max($bytes, 0); 
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
    $pow = min($pow, count($units) - 1); 
    
    $bytes /= pow(1024, $pow);
    
    return round($bytes, $precision) . ' ' . $units[$pow]; 
} 
?>

<div class="header-action">
    <h2 class="page-title" style="margin-bottom:0;">Penyimpanan Arsip</h2>
    <div>
        <form method="GET" action="" style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
            <input type="text" name="search" class="form-control" placeholder="Cari Nama Dokumen..." value="<?= htmlspecialchars($search) ?>" style="width:200px; padding: 8px 12px; border: 1px solid var(--border-color); border-radius: 4px;">
            <select name="tipe" class="form-control" onchange="this.form.submit()" style="width:200px;">
                <option value="masuk" <?= $tipe_arsip == 'masuk' ? 'selected' : '' ?>>📂 Data Arsip Masuk</option>
                <option value="keluar" <?= $tipe_arsip == 'keluar' ? 'selected' : '' ?>>📤 Data Arsip Keluar</option>
            </select>
            
            <select name="kategori" class="form-control" onchange="this.form.submit()" style="width:250px;">
                <option value="">-- Semua Kategori --</option>
                <?php foreach($kategori_list as $kat): ?>
                    <option value="<?= htmlspecialchars($kat) ?>" <?= $filter_kategori == $kat ? 'selected' : '' ?>>
                        <?= htmlspecialchars($kat) ?>
                    </option>
                <?php endforeach; ?>
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
                    <th>File</th>
                    <th>Nama Dokumen</th>
                    <th>Kategori</th>
                    <th>Status</th>
                    <th>Ukuran File</th>
                    <th style="text-align:center;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $table_name = ($tipe_arsip == 'keluar') ? 'surat_keluar' : 'surat_masuk';
                $folder_name = ($tipe_arsip == 'keluar') ? 'surat_keluar' : 'surat_masuk';
                
                $where_clauses = [];
                if (!empty($filter_kategori)) {
                    $escaped_kategori = mysqli_real_escape_string($conn, $filter_kategori);
                    $where_clauses[] = "kategori = '$escaped_kategori'";
                }
                if (!empty($search)) {
                    $escaped_search = mysqli_real_escape_string($conn, $search);
                    $where_clauses[] = "perihal LIKE '%$escaped_search%'";
                }
                
                $where_sql = count($where_clauses) > 0 ? "WHERE " . implode(" AND ", $where_clauses) : "";
                
                $query = mysqli_query($conn, "SELECT * FROM $table_name $where_sql ORDER BY id DESC");
                if(mysqli_num_rows($query) > 0) {
                    while($row = mysqli_fetch_assoc($query)) {
                        $file_path = "../uploads/{$folder_name}/" . $row['file_surat'];
                        $file_exists = !empty($row['file_surat']) && file_exists($file_path);
                        $secure_file_url = "../buka_file.php?folder={$folder_name}&file=" . urlencode($row['file_surat']);
                        
                        $file_size = '-';
                        if($file_exists) {
                            $file_size = formatBytes(filesize($file_path));
                        }

                        $ext = strtolower(pathinfo($row['file_surat'], PATHINFO_EXTENSION));
                        $is_image = in_array($ext, ['jpg', 'jpeg', 'png', 'gif']);
                        
                        $icon = '<i class="fas fa-file text-muted"></i>';
                        if($ext == 'pdf') $icon = '<i class="fas fa-file-pdf" style="color:#ef4444;"></i>';
                        else if(in_array($ext, ['doc', 'docx'])) $icon = '<i class="fas fa-file-word" style="color:#3b82f6;"></i>';
                        else if(in_array($ext, ['xls', 'xlsx'])) $icon = '<i class="fas fa-file-excel" style="color:#10b981;"></i>';
                        else if(in_array($ext, ['zip', 'rar', '7z'])) $icon = '<i class="fas fa-file-archive" style="color:#f59e0b;"></i>';
                        
                        $lihat_link = "../lihat_file.php?id={$row['id']}&tipe={$tipe_arsip}";

                        $file_display = "{$icon} " . htmlspecialchars($row['file_surat']);
                        if($file_exists && $is_image) {
                            $file_display = "<a href='{$lihat_link}' title='Lihat Dokumen'><img src='{$secure_file_url}' alt='preview' style='width:50px; height:50px; object-fit:cover; border-radius:4px; margin-right:8px; vertical-align:middle; border:1px solid #ddd;'> <span style='vertical-align:middle; color:var(--text-main);'>" . htmlspecialchars($row['file_surat']) . "</span></a>";
                        } else if ($file_exists) {
                            $file_display = "<a href='{$lihat_link}' style='text-decoration:none; color:inherit;' title='Lihat Dokumen'>{$icon} " . htmlspecialchars($row['file_surat']) . "</a>";
                        }
                        
                        $status_color = '#6b7280';
                        if($row['status'] == 'Divalidasi') $status_color = '#10b981';
                        else if($row['status'] == 'Dikembalikan') $status_color = '#ef4444';
                        else if($row['status'] == 'Dikirim ke Lurah') $status_color = '#3b82f6';

                        echo "<tr>
                            <td>{$no}</td>
                            <td>{$file_display}</td>
                            <td>".htmlspecialchars($row['perihal'])."</td>
                            <td>".htmlspecialchars($row['kategori'])."</td>
                            <td><span style='color:{$status_color}; font-weight:500;'>".htmlspecialchars($row['status'])."</span></td>
                            <td>{$file_size}</td>
                            <td style='text-align:center;'>";
                        if($file_exists) {
                            echo "<a href='{$secure_file_url}' download class='btn' style='padding:5px 10px; background:#3b82f6;' title='Download'><i class='fas fa-download'></i></a>";
                        } else {
                            echo "<button class='btn' style='padding:5px 10px; background:#9ca3af;' disabled><i class='fas fa-download'></i></button>";
                        }
                        echo "</td>
                        </tr>";
                        $no++;
                    }
                } else {
                    echo "<tr><td colspan='7' style='text-align:center;'>Belum ada data dokumen</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../footer.php'; ?>
