<?php
// admin_books_edit.php
require 'php/admin_check.php';
require 'php/config.php';
$conn = getConn();

$id = intval($_GET['id'] ?? 0);
$book = null;

if ($id > 0) {
    // Lấy thông tin sách bằng ID (SỬ DỤNG AS)
    $stmt = $conn->prepare("SELECT 
        s.IDSach AS id, 
        s.TenSach AS title, 
        tg.TenTacGia AS author, 
        tl.TenTheLoai AS category, 
        s.TinhTrang AS status 
    FROM sach s
    LEFT JOIN tacgia tg ON s.IDTacGia = tg.IDTacGia
    LEFT JOIN theloai tl ON s.IDTheLoai = tl.IDTheLoai
    WHERE s.IDSach = ? LIMIT 1");
    
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $book = $result->fetch_assoc();
    }
    $stmt->close();
}

$conn->close();

if (!$book) {
    header('Location: admin_books.php?error=' . urlencode('Không tìm thấy sách.'));
    exit;
}

$admin_username = htmlspecialchars($_SESSION['username'] ?? 'Admin');
?>
<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Sửa Sách</title>
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

<main class="edit-form-container">
    <h2>Sửa Sách: <?= htmlspecialchars($book['title']) ?></h2>

    <form action="php/admin_books_action.php" method="post">
        <input type="hidden" name="action" value="edit">
        <input type="hidden" name="id" value="<?= $book['id'] ?>">

        <label for="title">Tên Sách</label>
        <input type="text" id="title" name="title" value="<?= htmlspecialchars($book['title']) ?>" required>

        <label for="author">Tác giả</label>
        <input type="text" id="author" name="author" value="<?= htmlspecialchars($book['author'] ?? '') ?>" required>

        <label for="category">Thể loại</label>
        <input type="text" id="category" name="category" value="<?= htmlspecialchars($book['category'] ?? '') ?>" required>

        <label for="status">Trạng thái</label>
        <select id="status" name="status" required>
            <option value="available" <?= $book['status'] === 'available' ? 'selected' : '' ?>>Có sẵn</option>
            <option value="borrowed" <?= $book['status'] === 'borrowed' ? 'selected' : '' ?>>Đã mượn</option>
        </select>
        
        <button type="submit" class="btn">Lưu Thay Đổi</button>
    </form>

    <p><a href="admin_books.php">← Quay lại danh sách sách</a></p>
</main>

</body>
</html>