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
        s.Anh AS image,
        s.SoLuong AS quantity,
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
        <a href="admin_loans.php">Phiếu mượn</a>
        <a href="admin_stats.php">Thống kê</a>
    </nav>
    <div>
        <a class="greet-link" href="admin_profile.php">Xin chào, <?= $admin_username ?>!</a>
        <a class="logout-link" href="php/logout.php" style="margin-left:12px;">Đăng xuất</a>
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
        <p style="margin:6px 0 14px; color:#6b7280; font-size:13px;">
            Nhập 0 sẽ hiển thị trạng thái "Hết sách", lớn hơn 0 sẽ hiển thị "Có sẵn".
        </p>
        
        <button type="submit" class="btn">Lưu Thay Đổi</button>
    </form>

    <p><a href="admin_books.php">← Quay lại danh sách sách</a></p>
</main>

</body>
</html>