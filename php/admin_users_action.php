<?php
require 'admin_check.php';
require 'config.php';

$conn = getConn();
$id = intval($_POST['id'] ?? 0);

if ($id > 0) {
    // SỬA: DELETE FROM taikhoan WHERE IDTaiKhoan=? AND VaiTro!='admin'
    $stmt = $conn->prepare("DELETE FROM taikhoan WHERE IDTaiKhoan=? AND VaiTro!='admin'");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: ../admin_users.php");
exit;
?>