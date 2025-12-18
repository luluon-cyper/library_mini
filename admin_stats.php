<?php
require 'php/admin_check.php';
?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Thống kê & Báo cáo</title>
    <link rel="stylesheet" href="css/new_style.css?v=1"/>
</head>
<body>
<header class="app-header">
    <h1 class="logo">Admin Dashboard</h1>
    <nav class="admin-menu">
        <a href="admin.php">Tổng quan</a>
        <a href="admin_books.php">Quản lý sách</a>
        <a href="admin_users.php">Quản lý tài khoản</a>
        <a href="admin_loans.php">Phiếu mượn</a>
        <a href="admin_stats.php" class="active">Thống kê</a>
    </nav>
    <div>
        <a class="logout-link" href="php/logout.php">Đăng xuất</a>
    </div>
</header>

<main class="admin-section">
    <h2>Thống kê sách & mượn trả</h2>

    <div class="dashboard-summary" id="statsSummary">
        <div class="summary-card total-books">
            <h3>Tổng số bản sách</h3>
            <div class="count" id="bkTotal">...</div>
            <p class="detail">Tổng số bản sách (kể cả đang mượn)</p>
        </div>
        <div class="summary-card available-books">
            <h3>Có sẵn</h3>
            <div class="count" id="bkAvail">...</div>
            <p class="detail">Số bản đang còn kho</p>
        </div>
        <div class="summary-card borrowed-books">
            <h3>Đang mượn</h3>
            <div class="count" id="bkBorrow">...</div>
            <p class="detail">Số bản đang cho mượn</p>
        </div>
        <div class="summary-card total-users">
            <h3>Phiếu đang mở</h3>
            <div class="count" id="loanOpen">...</div>
            <p class="detail">Phiếu mượn chưa trả</p>
        </div>
    </div>

    <h3>Thống kê mượn trả</h3>
    <table class="admin-table">
        <tbody>
            <tr><th>Tổng phiếu</th><td id="loanTotal">...</td></tr>
            <tr><th>Đang mượn</th><td id="loanOpen2">...</td></tr>
            <tr><th>Đã trả</th><td id="loanClosed">...</td></tr>
            <tr><th>Quá hạn</th><td id="loanOver">...</td></tr>
            <tr><th>Phí phạt ghi nhận</th><td id="feeTotal">...</td></tr>
        </tbody>
    </table>

    <h3>Top thể loại được mượn nhiều</h3>
    <table class="admin-table" id="genreTable">
        <thead><tr><th>Thể loại</th><th>Số lần mượn</th></tr></thead>
        <tbody></tbody>
    </table>

    <h3>Xuất báo cáo (CSV)</h3>
    <div class="management-links">
        <a class="btn large-link" href="php/export_report.php?type=loans">Xuất báo cáo mượn trả</a>
        <a class="btn large-link outline" href="php/export_report.php?type=books">Xuất báo cáo sách</a>
    </div>
</main>

<script>
async function loadStats(){
    const res = await fetch('php/stats.php');
    const data = await res.json();
    const { books, loans, fees, top_genres } = data;
    document.getElementById('bkTotal').textContent = books.total;
    document.getElementById('bkAvail').textContent = books.available;
    document.getElementById('bkBorrow').textContent = books.borrowed;
    document.getElementById('loanOpen').textContent = loans.open;

    document.getElementById('loanTotal').textContent = loans.total;
    document.getElementById('loanOpen2').textContent = loans.open;
    document.getElementById('loanClosed').textContent = loans.closed;
    document.getElementById('loanOver').textContent = loans.overdue;
    document.getElementById('feeTotal').textContent = fees.total.toLocaleString('vi-VN');

    const tbody = document.querySelector('#genreTable tbody');
    tbody.innerHTML = '';
    (top_genres || []).forEach(g => {
        const tr = document.createElement('tr');
        tr.innerHTML = `<td>${g.category || 'Chưa rõ'}</td><td>${g.total}</td>`;
        tbody.appendChild(tr);
    });
}
document.addEventListener('DOMContentLoaded', loadStats);
</script>
</body>
</html>

