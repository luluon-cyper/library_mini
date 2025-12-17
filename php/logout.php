<?php
session_start();
// Hủy tất cả các biến session
$_SESSION = [];
// Hủy session
session_destroy();

// Chuyển hướng về index.php để index.php tự điều hướng đến login.html
header('Location: ../index.php');
exit;
?>