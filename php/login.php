<?php
session_start();
require 'config.php';
$conn = getConn();

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

$error_message = '';
$redirect_to_error = false;

if(empty($email) || empty($password)){
    $error_message = 'Vui lòng nhập email và mật khẩu.';
    $redirect_to_error = true;
} 

if(!$redirect_to_error) {
    // SỬA: Thay thế tên bảng và cột theo init.sql, dùng AS cho session
    $stmt = $conn->prepare('SELECT IDTaiKhoan AS user_id, HoTen AS username, MatKhau, VaiTro AS role FROM taikhoan WHERE Email = ? LIMIT 1');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if($row = $res->fetch_assoc()){
        // SỬA: Dùng cột MatKhau để xác thực
        if(password_verify($password, $row['MatKhau'])){ 
            
            // Gán Session theo tên biến đã định danh bằng AS
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['username'] = $row['username']; 
            $_SESSION['role'] = $row['role'];        
            
            // Chuyển hướng theo vai trò (role)
            if($row['role'] === 'admin'){
                header('Location: ../admin.php');
                exit;
            } else {
                header('Location: ../book-list.php'); 
                exit;
            }
        } else {
            $error_message = 'Thông tin đăng nhập không hợp lệ. Vui lòng kiểm tra lại Email và Mật khẩu.';
            $redirect_to_error = true;
        }
    } else {
        $error_message = 'Thông tin đăng nhập không hợp lệ. Vui lòng kiểm tra lại Email và Mật khẩu.';
        $redirect_to_error = true;
    }
}

if($redirect_to_error){
    $encoded_error = urlencode($error_message);
    header("Location: ../login.html?error={$encoded_error}");
    exit;
}

$conn->close();
?>