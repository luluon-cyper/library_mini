<?php
require 'php/admin_check.php';
require 'php/config.php';
$conn = getConn();
$admin_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT HoTen, Email FROM taikhoan WHERE IDTaiKhoan=? LIMIT 1");
$stmt->bind_param('i', $admin_id);
$stmt->execute();
$res = $stmt->get_result();
if(!$row = $res->fetch_assoc()){
    $stmt->close();
    $conn->close();
    header('Location: admin.php?error=' . urlencode('Không tìm thấy tài khoản.'));
    exit;
}
$stmt->close();
$conn->close();

$admin_username = htmlspecialchars($row['HoTen'] ?? 'Admin');
$admin_email = htmlspecialchars($row['Email'] ?? '');
$msg_success = isset($_GET['success']) ? htmlspecialchars($_GET['success']) : '';
$msg_error = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : '';
?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Thông tin tài khoản (Admin)</title>
    <link rel="stylesheet" href="css/style.css?v=1"/>
</head>
<body class="auth-page">
    <div class="auth-container">
        <h2>Thông tin tài khoản (Admin)</h2>

        <?php if($msg_error): ?>
            <p class="alert error"><?= $msg_error ?></p>
        <?php endif; ?>
        <?php if($msg_success): ?>
            <p class="alert success"><?= $msg_success ?></p>
        <?php endif; ?>

        <form action="php/profile_update_admin.php" method="post" class="form-grid" style="text-align:left;">
            <div class="form-field">
                <label>Họ tên</label>
                <input type="text" name="name" value="<?= $admin_username ?>" required>
            </div>
            <div class="form-field">
                <label>Email</label>
                <input type="email" name="email" value="<?= $admin_email ?>">
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
                <a href="admin.php" class="btn outline" style="margin-left:10px;">← Quay lại Dashboard</a>
            </div>
        </form>
    </div>
</body>
</html>

