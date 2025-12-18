<?php
require 'php/admin_check.php';
$admin_username = htmlspecialchars($_SESSION['username'] ?? 'Admin');
?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Quản lý Phiếu mượn</title>
    <link rel="stylesheet" href="css/new_style.css"/>
</head>
<body>
<header class="app-header">
    <h1 class="logo">Admin Dashboard</h1>
    <nav class="admin-menu">
        <a href="admin.php">Tổng quan</a>
        <a href="admin_books.php">Quản lý sách</a>
        <a href="admin_users.php">Quản lý tài khoản</a>
        <a href="admin_loans.php" class="active">Phiếu mượn</a>
        <a href="admin_stats.php">Thống kê</a>
    </nav>
    <div>
        <a class="greet-link" href="admin_profile.php">Xin chào, <?= $admin_username ?>!</a>
        <a class="logout-link" href="php/logout.php" style="margin-left:12px;">Đăng xuất</a>
    </div>
</header>

<main class="admin-section">
    <h2>Quản lý Phiếu mượn</h2>

    <div class="add-form">
        <h3>Tạo phiếu mượn</h3>
        <form action="php/loans_create.php" method="post" class="form-grid">
            <div class="form-field">
                <label>ID Tài khoản người mượn</label>
                <input type="number" name="user_id" min="1" placeholder="Ví dụ: 5" required>
            </div>
            <div class="form-field" style="grid-column: 1 / -1;">
                <label>Danh sách sách </label>
                <textarea name="items" rows="4" style="width:100%; padding:10px; border:1px solid var(--border); border-radius:10px;" placeholder="1,1&#10;3,2" required></textarea>
                <small class="hint">Mỗi dòng: IDSach, số lượng. Sẽ trừ tồn kho khi tạo.</small>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn primary">Tạo phiếu</button>
            </div>
        </form>
    </div>

    <h3>Danh sách Phiếu mượn</h3>
    <div class="card-table">
        <table class="admin-table" id="loanTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Người mượn</th>
                    <th>Ngày mượn</th>
                    <th>Hạn trả</th>
                    <th>Trạng thái</th>
                    <th>Sách</th>
                    <th>Thao tác</th>
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
        const actions = l.TrangThaiMuonTra === 'dangmuon'
            ? `<form action="php/loans_extend.php" method="post" style="display:inline-block;margin-right:6px;">
                    <input type="hidden" name="loan_id" value="${l.id}">
                    <button class="btn outline" style="padding:6px 10px;">Gia hạn</button>
               </form>
               <form action="php/loans_return.php" method="post" style="display:inline-block;" onsubmit="return confirm('Xác nhận trả sách?');">
                    <input type="hidden" name="loan_id" value="${l.id}">
                    <button class="btn" style="padding:6px 10px;background:#22c55e;">Trả</button>
               </form>`
            : '';
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${l.id}</td>
            <td>${l.user_name || ('User ' + l.user_id)}</td>
            <td>${l.NgayMuon}</td>
            <td>${l.NgayHenTra}</td>
            <td>${status}</td>
            <td>${items || '---'}</td>
            <td>${actions}</td>
        `;
        tbody.appendChild(tr);
    });
}
document.addEventListener('DOMContentLoaded', loadLoans);
</script>
</body>
</html>

