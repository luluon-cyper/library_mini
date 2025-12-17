<?php
session_start();
// Kiểm tra IDTaiKhoan
if(!isset($_SESSION['user_id'])){ // Vẫn dùng 'user_id' vì được set trong login.php
    header('Location: ../login.html?error=' . urlencode('Vui lòng đăng nhập để tiếp tục.'));
    exit;
}
// Kiểm tra VaiTro
if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'){ // Vẫn dùng 'role' vì được set trong login.php
    header('Location: ../admin.php');
    exit;
}
?>