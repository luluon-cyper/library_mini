<?php
require 'config.php';
$conn = getConn();

$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if(!$username || !$email || !$password){
    $error_message = urlencode('Vui lòng nhập đầy đủ thông tin.');
    header("Location: ../register.html?error={$error_message}");
    exit;
}

// check duplicate. SỬA: SELECT IDTaiKhoan FROM taikhoan
$stmt = $conn->prepare('SELECT IDTaiKhoan FROM taikhoan WHERE Email = ? LIMIT 1');
$stmt->bind_param('s', $email);
$stmt->execute();
$stmt->store_result();
if($stmt->num_rows > 0){
    $error_message = urlencode('Email đã được đăng ký.');
    header("Location: ../register.html?error={$error_message}");
    exit;
}
$stmt->close();

$hash = password_hash($password, PASSWORD_DEFAULT);
$role = 'user';
// SỬA: INSERT INTO taikhoan (HoTen, Email, MatKhau, VaiTro)
$stmt = $conn->prepare('INSERT INTO taikhoan (HoTen, Email, MatKhau, VaiTro) VALUES (?, ?, ?, ?)');
$stmt->bind_param('ssss', $username, $email, $hash, $role);
if($stmt->execute()){
    $success_message = urlencode('Đăng ký thành công! Vui lòng đăng nhập.');
    header("Location: ../login.html?success={$success_message}");
    exit;
} else {
    $error_message = urlencode('Đăng ký thất bại: Đã xảy ra lỗi hệ thống.');
    header("Location: ../register.html?error={$error_message}");
    exit;
}
?>