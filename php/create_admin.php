<?php
require 'config.php';
$conn = getConn();

// Thay bằng email & mật khẩu admin bạn muốn
$admin_name = 'Admin';
$admin_email = 'admin@example.com';
$admin_password = 'Admin123!'; // đổi ngay sau khi đăng nhập

// kiểm tra nếu đã tồn tại
$stmt = $conn->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
$stmt->bind_param('s', $admin_email);
$stmt->execute();
$stmt->store_result();
if($stmt->num_rows > 0){
    echo "Admin đã tồn tại.";
    exit;
}

$hash = password_hash($admin_password, PASSWORD_DEFAULT);
$stmt = $conn->prepare('INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)');
$role = 'admin';
$stmt->bind_param('ssss', $admin_name, $admin_email, $hash, $role);
if($stmt->execute()){
    echo "Tạo admin thành công. Email: $admin_email , password: $admin_password";
} else {
    echo "Lỗi: " . $conn->error;
}
?>
