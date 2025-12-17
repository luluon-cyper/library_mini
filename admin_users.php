<?php
// admin_users.php
require 'php/admin_check.php';
require 'php/config.php';
$conn = getConn();

// Lấy danh sách tài khoản người dùng (VaiTro='user')
// Dùng AS để đổi tên cột cho dễ đọc
$query = "SELECT 
    IDTaiKhoan AS id, 
    HoTen AS username, 
    Email, 
    VaiTro AS role 
FROM taikhoan 
WHERE VaiTro = 'user'
ORDER BY IDTaiKhoan DESC";

$res = $conn->query($query);
$users = [];
while($r = $res->fetch_assoc()) $users[] = $r;

$admin_username = htmlspecialchars($_SESSION['username'] ?? 'Admin');
$conn->close();
?>
<!doctype html>
<html lang="vi">

<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Quản lý Tài khoản</title>
  <link rel="stylesheet" href="css/new_style.css"/>
</head>

<body>

<header class="app-header">
    <h1 class="logo">Admin Dashboard</h1>
    <nav class="admin-menu">
        <a href="admin.php">Tổng quan</a>
        <a href="admin_books.php">Quản lý sách</a>
        <a href="admin_users.php" class="active">Quản lý tài khoản</a>
    </nav>
    <div>
        <span>Xin chào, **<?= $admin_username ?>**</span>
        <a class="logout-link" href="php/logout.php">Đăng xuất</a>
    </div>
</header>

<main class="admin-section">
    <h2>Quản lý Tài khoản Người dùng</h2>

    <h3>Danh sách Người dùng</h3>
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Họ Tên</th>
                <th>Email</th>
                <th>Vai Trò</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($users)): ?>
                <tr>
                    <td colspan="5">Không có tài khoản người dùng nào.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['Email']) ?></td>
                        <td><?= htmlspecialchars($user['role']) ?></td>
                        <td>
                            <form action="php/admin_users_action.php" method="post" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tài khoản này?');">
                                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                <button type="submit" class="btn" style="background:#e74c3c; padding: 5px 10px; font-size: 0.9rem;">Xóa</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</main>

</body>
</html>