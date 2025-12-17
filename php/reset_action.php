<?php
require 'config.php';
$conn = getConn();

$token = trim($_POST['token'] ?? '');
$new_password = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';
$error_message = '';

if (empty($token) || empty($new_password) || empty($confirm_password)) {
    $error_message = 'Vui lòng điền đầy đủ thông tin.';
} else if ($new_password !== $confirm_password) {
    $error_message = 'Mật khẩu mới và xác nhận mật khẩu không khớp.';
} else {
    // 1. Tìm token và kiểm tra thời gian hết hạn
    $stmt = $conn->prepare('SELECT id FROM users WHERE reset_token = ? AND token_expire_at > NOW() LIMIT 1');
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($row = $res->fetch_assoc()) {
        $user_id = $row['id'];

        // 2. Mã hóa mật khẩu mới
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // 3. Cập nhật mật khẩu và xóa token (để token không thể sử dụng lại)
        $stmt_update = $conn->prepare('UPDATE users SET password = ?, reset_token = NULL, token_expire_at = NULL WHERE id = ?');
        $stmt_update->bind_param('si', $hashed_password, $user_id);
        
        if ($stmt_update->execute()) {
            // Thành công: Chuyển hướng về trang đăng nhập với thông báo thành công
            header('Location: ../login.html?success=Mật khẩu của bạn đã được cập nhật. Vui lòng đăng nhập.');
            exit;
        } else {
            $error_message = 'Lỗi hệ thống khi cập nhật mật khẩu.';
        }

    } else {
        // Token không tìm thấy hoặc đã hết hạn
        $error_message = 'Liên kết khôi phục không hợp lệ hoặc đã hết hạn.';
    }
}

// Xử lý lỗi
if ($error_message) {
    $encoded_error = urlencode($error_message);
    // Chuyển hướng về reset.html, truyền lại token để giữ form
    header("Location: ../reset.html?token={$token}&error={$encoded_error}");
    exit;
}
?>