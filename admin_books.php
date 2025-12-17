<?php
// admin_books.php
require 'php/admin_check.php';
require 'php/config.php';
$conn = getConn();

// Lấy danh sách sách với thông tin tác giả và thể loại (SỬ DỤNG AS CHO TÊN BIẾN DỄ DÙNG)
$query = "SELECT 
    s.IDSach AS id, 
    s.TenSach AS title, 
    tg.TenTacGia AS author, 
    tl.TenTheLoai AS category, 
    s.TinhTrang AS status 
FROM sach s
LEFT JOIN tacgia tg ON s.IDTacGia = tg.IDTacGia
LEFT JOIN theloai tl ON s.IDTheLoai = tl.IDTheLoai
ORDER BY s.IDSach DESC";

$res = $conn->query($query);
$books = [];
while($r = $res->fetch_assoc()) $books[] = $r;

$admin_username = htmlspecialchars($_SESSION['username'] ?? 'Admin');
$conn->close();
?>
<!doctype html>
<html lang="vi">

<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Quản lý Sách</title>
  <link rel="stylesheet" href="css/new_style.css"/>
</head>

<body>

<header class="app-header">
    <h1 class="logo">Admin Dashboard</h1>
    <nav class="admin-menu">
        <a href="admin.php">Tổng quan</a>
        <a href="admin_books.php" class="active">Quản lý sách</a>
        <a href="admin_users.php">Quản lý tài khoản</a>
    </nav>
    <div>
        <span>Xin chào, **<?= $admin_username ?>**</span>
        <a class="logout-link" href="php/logout.php">Đăng xuất</a>
    </div>
</header>

<main class="admin-section">
    <h2>Quản lý Sách</h2>

    <div class="add-form">
        <h3>Thêm Sách Mới</h3>
        <form action="php/admin_books_action.php" method="post">
            <input type="hidden" name="action" value="add">
            <div class="form-row">
                <input type="text" name="title" placeholder="Tên sách" required>
                <input type="text" name="author" placeholder="Tác giả" required>
            </div>
            <div class="form-row">
                <input type="text" name="category" placeholder="Thể loại" required>
                <select name="status" required>
                    <option value="available">Có sẵn</option>
                    <option value="borrowed">Đã mượn</option>
                </select>
            </div>
            <button type="submit" class="btn">Thêm Sách</button>
        </form>
    </div>

    <h3>Danh sách Sách</h3>
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên Sách</th>
                <th>Tác giả</th>
                <th>Thể loại</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($books)): ?>
                <tr>
                    <td colspan="6">Không có sách nào trong thư viện.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($books as $book): ?>
                    <tr>
                        <td><?= htmlspecialchars($book['id']) ?></td>
                        <td><?= htmlspecialchars($book['title']) ?></td>
                        <td><?= htmlspecialchars($book['author'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($book['category'] ?? 'N/A') ?></td>
                        <td>
                            <span class="book-status <?= $book['status'] ?>">
                                <?= $book['status'] === 'available' ? 'Có sẵn' : 'Đã mượn' ?>
                            </span>
                        </td>
                        <td>
                            <a href="admin_books_edit.php?id=<?= $book['id'] ?>" class="btn outline" style="padding: 5px 10px; font-size: 0.9rem;">Sửa</a>
                            <form action="php/admin_books_action.php" method="post" style="display:inline-block; margin-left: 5px;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sách này?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $book['id'] ?>">
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