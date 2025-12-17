<?php
// index.php
session_start();
if(isset($_SESSION['user_id'])){
    // Đã đăng nhập, chuyển hướng theo vai trò (role)
    if($_SESSION['role'] === 'admin'){
        header('Location: admin.php');
    } else {
        header('Location: book-list.php');
    }
} else {
    // Chưa đăng nhập, chuyển hướng đến trang đăng nhập
    header('Location: login.html');
}
exit;
?>