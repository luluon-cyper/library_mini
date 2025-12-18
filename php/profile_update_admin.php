<?php
require 'admin_check.php';
require 'config.php';
$conn = getConn();
$user_id = $_SESSION['user_id'] ?? 0;

function redirect_with($param, $msg){
    header('Location: ../admin_profile.php?' . $param . '=' . urlencode($msg));
    exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$current_password = $_POST['current_password'] ?? '';
$new_password = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

if(!$user_id){
    redirect_with('error', 'Không xác định được tài khoản.');
}
if($name === ''){
    redirect_with('error', 'Họ tên không được để trống.');
}

// Lấy thông tin hiện tại
$stmt = $conn->prepare("SELECT MatKhau FROM taikhoan WHERE IDTaiKhoan=? LIMIT 1");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$stmt->bind_result($dbPass);
if(!$stmt->fetch()){
    $stmt->close();
    redirect_with('error', 'Không tìm thấy tài khoản.');
}
$stmt->close();

$update_password = false;
$hash = null;

// Nếu yêu cầu đổi mật khẩu
if($new_password !== '' || $confirm_password !== ''){
    if($new_password !== $confirm_password){
        redirect_with('error', 'Mật khẩu mới và xác nhận không khớp.');
    }
    if($current_password === ''){
        redirect_with('error', 'Vui lòng nhập mật khẩu hiện tại.');
    }
    if(!password_verify($current_password, $dbPass)){
        redirect_with('error', 'Mật khẩu hiện tại không đúng.');
    }
    $hash = password_hash($new_password, PASSWORD_DEFAULT);
    $update_password = true;
}

if($update_password){
    $stmt = $conn->prepare("UPDATE taikhoan SET HoTen=?, Email=?, MatKhau=? WHERE IDTaiKhoan=?");
    $stmt->bind_param('sssi', $name, $email, $hash, $user_id);
} else {
    $stmt = $conn->prepare("UPDATE taikhoan SET HoTen=?, Email=? WHERE IDTaiKhoan=?");
    $stmt->bind_param('ssi', $name, $email, $user_id);
}

if(!$stmt->execute()){
    $stmt->close();
    redirect_with('error', 'Cập nhật không thành công.');
}
$stmt->close();

// Cập nhật session
$_SESSION['username'] = $name;
if($email) $_SESSION['email'] = $email;

redirect_with('success', 'Cập nhật thông tin thành công.');
?>

