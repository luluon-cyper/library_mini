<?php
session_start();
if(!isset($_SESSION['user_id'])){ 
    header('Location: ../login.php?error=' . urlencode('Vui lòng đăng nhập để tiếp tục.'));
    exit;
}
if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'){
    header('Location: ../admin.php');
    exit;
}
?>