<?php
// admin.php
require 'php/admin_check.php';
require 'php/config.php';
$conn = getConn();

// Lấy số liệu thống kê
$total_books = $conn->query("SELECT COUNT(IDSach) FROM sach")->fetch_row()[0];
$available_books = $conn->query("SELECT COUNT(IDSach) FROM sach WHERE TinhTrang='available'")->fetch_row()[0];
$borrowed_books = $conn->query("SELECT COUNT(IDSach) FROM sach WHERE TinhTrang='borrowed'")->fetch_row()[0];
$total_users = $conn->query("SELECT COUNT(IDTaiKhoan) FROM taikhoan WHERE VaiTro='user'")->fetch_row()[0];

$conn->close();

$admin_username = htmlspecialchars($_SESSION['username'] ?? 'Admin'); 
?>
<!doctype html>
<html lang="vi">

<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="css/new_style.css"/>
</head>

<body>

<header class="app-header">
    <h1 class="logo">Admin Dashboard</h1>
    <nav class="admin-menu">
        <a href="admin.php" class="active">Tổng quan</a>
        <a href="admin_books.php">Quản lý sách</a>
        <a href="admin_users.php">Quản lý tài khoản</a>
        <a href="admin_loans.php">Phiếu mượn</a>
    </nav>
    <div>
        <span>Xin chào, <?= $admin_username ?>!</span>
        <a class="logout-link" href="php/logout.php">Đăng xuất</a>
    </div>
</header>

<main class="admin-section">
    <h2>Chào mừng, Admin Quản lý</h2>
    <p>Sử dụng menu bên trên để truy cập các chức năng quản lý.</p>

    <div class="dashboard-summary">
        <div class="summary-card total-books">
            <h3>Tổng số sách</h3>
            <div class="count"><?= $total_books ?></div>
            <p class="detail">Tổng sách hiện có trong thư viện</p>
            <a href="admin_books.php">Xem danh sách sách</a>
        </div>
        <div class="summary-card available-books">
            <h3>Sách Có sẵn</h3>
            <div class="count"><?= $available_books ?></div>
            <p class="detail">Số lượng sách đang cho mượn</p>
        </div>
        <div class="summary-card borrowed-books">
            <h3>Sách Đã mượn</h3>
            <div class="count"><?= $borrowed_books ?></div>
            <p class="detail">Số lượng sách đang được độc giả mượn</p>
        </div>
        <div class="summary-card total-users">
            <h3>Tổng người dùng</h3>
            <div class="count"><?= $total_users ?></div>
            <p class="detail">Tổng tài khoản người dùng (trừ admin)</p>
            <a href="admin_users.php">Quản lý tài khoản</a>
        </div>
    </div>
    
</main>

</body>
</html>