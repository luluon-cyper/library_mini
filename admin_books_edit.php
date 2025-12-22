<?php
require 'php/admin_check.php';
require 'php/config.php';
$conn = getConn();

$id = intval($_GET['id'] ?? 0);
$book = null;

if ($id > 0) {
    $stmt = $conn->prepare("SELECT 
        s.IDSach AS id, 
        s.TenSach AS title, 
        s.Anh AS image,
        s.SoLuong AS quantity,
        tg.TenTacGia AS author, 
        tl.TenTheLoai AS category, 
        s.TinhTrang AS status,
        c.MoTa AS description,
        c.NamXuatBan AS year,
        c.NhaXuatBan AS publisher,
        c.NgonNgu AS language,
        c.SoTrang AS pages
    FROM sach s
    LEFT JOIN ct_sach c ON c.IDSach = s.IDSach
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
    <link rel="stylesheet" href="css/style.css"/>
</head>

<body>

<header class="app-header">
    <h1 class="logo">Admin Dashboard</h1>
    <nav class="admin-menu">
        <a href="admin.php">Tổng quan</a>
        <a href="admin_books.php" class="active">Quản lý sách</a>
        <a href="admin_users.php">Quản lý tài khoản</a>
        <a href="admin_loans.php">Phiếu mượn</a>
        <a href="admin_stats.php">Thống kê</a>
    </nav>
    <div class="user-actions">
        <span class="">Xin chào, <?= $admin_username ?>!</span>
        <a class="logout-link" href="admin_profile.php">Thông tin tài khoản</a>
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

        <label for="image">Ảnh (URL)</label>
        <input type="url" id="image" name="image" value="<?= htmlspecialchars($book['image'] ?? '') ?>" placeholder="https://example.com/image.jpg">
        <p style="margin:6px 0 14px; color:#6b7280; font-size:13px;">
            Để trống sẽ dùng ảnh mặc định.
        </p>

        <label for="quantity">Số lượng</label>
        <input type="number" id="quantity" name="quantity" min="0" value="<?= (int)$book['quantity'] ?>" required>

        <label for="description">Mô tả</label>
        <textarea id="description" name="description" rows="5" placeholder="Tóm tắt nội dung sách..." style="min-height:120px;"><?= htmlspecialchars($book['description'] ?? '') ?></textarea>

        <label for="year">Năm xuất bản</label>
        <input type="number" id="year" name="year" min="0" value="<?= htmlspecialchars($book['year'] ?? '') ?>">

        <label for="publisher">Nhà xuất bản</label>
        <input type="text" id="publisher" name="publisher" value="<?= htmlspecialchars($book['publisher'] ?? '') ?>" placeholder="NXB Trẻ">

        <label for="language">Ngôn ngữ</label>
        <input type="text" id="language" name="language" value="<?= htmlspecialchars($book['language'] ?? '') ?>" placeholder="Tiếng Việt">

        <label for="pages">Số trang</label>
        <input type="number" id="pages" name="pages" min="0" value="<?= htmlspecialchars($book['pages'] ?? '') ?>">

        <button type="submit" class="btn">Lưu Thay Đổi</button>
    </form>

    <p><a href="admin_books.php">← Quay lại danh sách sách</a></p>
</main>

</body>
</html>