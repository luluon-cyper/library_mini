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
    $stmt = $conn->prepare('SELECT id FROM users WHERE reset_token = ? AND token_expire_at > NOW() LIMIT 1');
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($row = $res->fetch_assoc()) {
        $user_id = $row['id'];

        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        $stmt_update = $conn->prepare('UPDATE users SET password = ?, reset_token = NULL, token_expire_at = NULL WHERE id = ?');
        $stmt_update->bind_param('si', $hashed_password, $user_id);
        
        if ($stmt_update->execute()) {
            header('Location: ../login.php?success=Mật khẩu của bạn đã được cập nhật. Vui lòng đăng nhập.');
            exit;
        } else {
            $error_message = 'Lỗi hệ thống khi cập nhật mật khẩu.';
        }

    } else {
        $error_message = 'Liên kết khôi phục không hợp lệ hoặc đã hết hạn.';
    }
}

if ($error_message) {
    $encoded_error = urlencode($error_message);
    header("Location: ../reset.html?token={$token}&error={$encoded_error}");
    exit;
}
?>