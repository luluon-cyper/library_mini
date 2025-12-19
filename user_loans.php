<?php
require 'php/auth_check.php';
// Tên biến 'username' đã được set trong login.php thông qua AS HoTen
$username = htmlspecialchars($_SESSION['username'] ?? 'Người dùng'); 
?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Phiếu mượn của tôi</title>
    <link rel="stylesheet" href="css/new_style.css"/>
</head>
<body>
<header class="app-header user-header">
    <h1 class="logo"><a class="logo-link" href="book-list.php">📚 Thư viện Mini</a></h1>
    <div class="user-actions">
        <a class="logout-link">Xin chào, <strong><?= $username ?></strong></a>
        <a class="logout-link" href="profile.php">thông tin tài khoản</a>
        <a class="logout-link" href="book-list.php">trang chủ</a>
        <a class="logout-link" href="php/logout.php">Đăng xuất</a>
    </div>
</header>

<main class="admin-section">
    <h2>Phiếu mượn của tôi</h2>
    <div class="card-table">
        <table class="admin-table" id="loanTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ngày mượn</th>
                    <th>Hạn trả</th>
                    <th>Trạng thái</th>
                    <th>Sách</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</main>

<script>
async function loadLoans(){
    const res = await fetch('php/loans_list.php');
    const data = await res.json();
    const tbody = document.querySelector('#loanTable tbody');
    tbody.innerHTML = '';
    data.loans.forEach(l => {
        const items = (data.items[l.id] || []).map(it => `${it.TenSach || 'Sách'} (x${it.SoLuong})${it.NgayTra ? ' - Đã trả' : ''}${it.PhiPhat > 0 ? ' - Phí: ' + it.PhiPhat : ''}`).join('<br>');
        const status = l.TrangThaiMuonTra === 'dangmuon' ? 'Đang mượn' : (l.TrangThaiMuonTra === 'datra' ? 'Đã trả' : 'Quá hạn');
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${l.id}</td>
            <td>${l.NgayMuon}</td>
            <td>${l.NgayHenTra}</td>
            <td>${status}</td>
            <td>${items || '---'}</td>
        `;
        tbody.appendChild(tr);
    });
}
document.addEventListener('DOMContentLoaded', loadLoans);
</script>
</body>
</html>

