<?php
header('Content-Type: application/json');
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Phương thức không hợp lệ'
    ]);
    exit;
}

$email = trim($_POST['email'] ?? '');

if ($email === '') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Vui lòng nhập email'
    ]);
    exit;
}

$conn = getConn();

// SỬA: SELECT IDTaiKhoan FROM taikhoan
$stmt = $conn->prepare("SELECT IDTaiKhoan FROM taikhoan WHERE Email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {

    echo json_encode([
        'status' => 'success',
        'message' => 'Email hợp lệ! Vui lòng kiểm tra email để đặt lại mật khẩu.'
    ]);

} else {

    echo json_encode([
        'status' => 'error',
        'message' => 'Email không tồn tại trong hệ thống.'
    ]);
}

$stmt->close();
$conn->close(); 
?>