<?php
// admin.php
require 'php/admin_check.php';
require 'php/config.php';
$conn = getConn();

// Lấy số liệu thống kê
// Bản sách đang mượn theo phiếu chưa trả (TrangThaiMuonTra = 'dangmuon')
$borrowed_books = (int)$conn->query("
    SELECT COALESCE(SUM(ct.SoLuong),0)
    FROM phieumuon pm
    JOIN ct_phieumuon ct ON pm.IDPhieuMuon = ct.IDPhieuMuon
    WHERE pm.TrangThaiMuonTra = 'dangmuon'
")->fetch_row()[0];
// Bản sách còn trong kho (SoLuong hiện tại của bảng sach)
$available_books = (int)$conn->query("SELECT COALESCE(SUM(SoLuong),0) FROM sach")->fetch_row()[0];
// Tổng số bản sách = còn kho + đang mượn
$total_copies = $available_books + $borrowed_books;
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
        <a href="admin_stats.php">Thống kê</a>
    </nav>
    <div class="user-actions">
        <a class="greet-link">Xin chào, <?= $admin_username ?>!</a>
        <a class="greet-link" href="admin_profile.php">Thông tin tài khoản</a>
        <a class="logout-link" href="php/logout.php">Đăng xuất</a>
    </div>
</header>

<main class="admin-section">
    <h2>Chào mừng, Admin Quản lý</h2>

    <div class="dashboard-summary">
        <div class="summary-card total-books">
            <h3>Tổng số bản sách</h3>
            <div class="count"><?= $total_copies ?></div>
            <p class="detail">Tổng số bản sách</p>
            <a href="admin_books.php">Xem danh sách sách</a>
        </div>
        <div class="summary-card available-books">
            <h3>Sách Có sẵn</h3>
            <div class="count"><?= $available_books ?></div>
            <p class="detail">Số bản sách đang có sẵn</p>
        </div>
        <div class="summary-card borrowed-books">
            <h3>Sách đang mượn</h3>
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