<?php
require 'php/auth_check.php'; // user session
require 'php/config.php';
$conn = getConn();
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT HoTen, Email FROM taikhoan WHERE IDTaiKhoan=? LIMIT 1");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$res = $stmt->get_result();
if(!$row = $res->fetch_assoc()){
    header('Location: book-list.php?error=' . urlencode('Không tìm thấy tài khoản.'));
    exit;
}
$stmt->close();
$conn->close();
$username = htmlspecialchars($row['HoTen'] ?? 'Người dùng');
$email = htmlspecialchars($row['Email'] ?? '');
$msg_success = isset($_GET['success']) ? htmlspecialchars($_GET['success']) : '';
$msg_error = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : '';
?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin tài khoản</title>
    <link rel="stylesheet" href="css/style.css?v=1">
</head>
<body class="auth-page">
    <div class="auth-container">
        <h2>Thông tin tài khoản</h2>

        <?php if($msg_error): ?>
            <p class="alert error"><?= $msg_error ?></p>
        <?php endif; ?>
        <?php if($msg_success): ?>
            <p class="alert success"><?= $msg_success ?></p>
        <?php endif; ?>

        <form action="php/profile_update.php" method="post" class="form-grid" style="text-align:left;">
            <div class="form-field">
                <label>Họ tên</label>
                <input type="text" name="name" value="<?= $username ?>" required>
            </div>
            <div class="form-field">
                <label>Email</label>
                <input type="email" name="email" value="<?= $email ?>">
            </div>
            <div class="form-field">
                <label>Mật khẩu hiện tại (bắt buộc nếu đổi mật khẩu)</label>
                <input type="password" name="current_password" placeholder="Nhập mật khẩu hiện tại">
            </div>
            <div class="form-field">
                <label>Mật khẩu mới</label>
                <input type="password" name="new_password" placeholder="Để trống nếu không đổi">
            </div>
            <div class="form-field">
                <label>Nhập lại mật khẩu mới</label>
                <input type="password" name="confirm_password" placeholder="Để trống nếu không đổi">
            </div>
            <div class="form-actions" style="justify-content:flex-start;">
                <button type="submit" class="btn primary">Lưu thay đổi</button>
                <a href="book-list.php" class="btn outline" style="margin-left:10px;">← Quay lại trang sách</a>
            </div>
        </form>
    </div>
</body>
</html>

