<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = md5($_POST['password']); // Using md5 as per db.sql setup
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password' AND role = '$role'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nama'] = $user['nama'];
        $_SESSION['role'] = $user['role'];
        
        if ($_SESSION['role'] == 'admin') {
            header("Location: " . BASE_URL . "admin/dashboard_admin.php");
        } elseif ($_SESSION['role'] == 'sekretaris') {
            header("Location: " . BASE_URL . "sekretaris/dashboard_sekretaris.php");
        } elseif ($_SESSION['role'] == 'lurah') {
            header("Location: " . BASE_URL . "lurah/dashboard_lurah.php");
        }
        exit();
    } else {
        $_SESSION['error'] = "Username atau password salah!";
        header("Location: index.php");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>
