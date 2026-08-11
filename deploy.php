<?php
// ==========================================
// AUTO DEPLOY SCRIPT UNTUK GITHUB WEBHOOK
// ==========================================

// Ganti secret ini dengan kata sandi rahasia Anda.
// Anda juga harus memasukkan secret ini di pengaturan Webhook GitHub.
$secret = 'arsipdigital_aman_123';

// Ambil signature dari GitHub
$signature = $_SERVER['HTTP_X_HUB_SIGNATURE'] ?? '';

// Verifikasi (Opsional tapi direkomendasikan agar tidak ada orang usil yang nge-hit URL ini)
if ($signature) {
    $payload = file_get_contents('php://input');
    $hash = 'sha1=' . hash_hmac('sha1', $payload, $secret);
    
    if (!hash_equals($hash, $signature)) {
        http_response_code(403);
        die('Akses ditolak: Signature tidak valid.');
    }
}

// Jalankan perintah git pull
// Karena repo bersifat public, ini tidak memerlukan password/SSH key tambahan
$output = shell_exec('git pull origin master 2>&1');

// Tampilkan output
echo "<pre>";
echo "Waktu Deploy: " . date('Y-m-d H:i:s') . "\n";
echo "Git Output:\n";
echo htmlspecialchars($output);
echo "</pre>";

// Simpan ke log
file_put_contents('deploy.log', date('Y-m-d H:i:s') . " - Output: " . trim($output) . "\n", FILE_APPEND);
?>
