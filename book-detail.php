<?php
require 'php/auth_check.php';
require 'php/config.php';
$conn = getConn();

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: book-list.php?error=' . urlencode('Thiếu mã sách.'));
    exit;
}

$sql = "
SELECT 
    s.IDSach AS id,
    s.TenSach AS title,
    s.Anh AS image,
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
WHERE s.IDSach = ?
LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
$book = $res->fetch_assoc();
$stmt->close();

if (!$book) {
    header('Location: book-list.php?error=' . urlencode('Không tìm thấy sách.'));
    exit;
}
$reviews = [];
$stmtRv = $conn->prepare("
    SELECT 
        d.SoSao AS rating,
        d.NoiDungDanhGia AS content,
        d.NgayDanhGia AS date,
        tk.HoTen AS username
    FROM danhgia d
    JOIN taikhoan tk ON tk.IDTaiKhoan = d.IDTaiKhoan
    WHERE d.IDSach = ?
    ORDER BY d.NgayDanhGia DESC, d.IDDanhGia DESC
");
$stmtRv->bind_param('i', $id);
$stmtRv->execute();
$resRv = $stmtRv->get_result();
while ($row = $resRv->fetch_assoc()) {
    $reviews[] = $row;
}
$stmtRv->close();
$conn->close();

$username = htmlspecialchars($_SESSION['username'] ?? 'Người dùng');
$fallback_img = 'https://dayve.vn/wp-content/uploads/2022/11/Ve-quyen-sach-Buoc-16.jpg';
$image = $book['image'] ?: $fallback_img;
$statusClass = $book['status'] === 'available' ? 'available' : 'borrowed';
$statusText = $book['status'] === 'available' ? 'Có sẵn' : 'Đã mượn';
$msg = $_GET['msg'] ?? '';
?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title><?= htmlspecialchars($book['title']) ?> - Chi tiết sách</title>
    <link rel="stylesheet" href="css/style.css?v=1"/>
</head>
<body>
<header class="app-header user-header">
    <h1 class="logo"><a class="logo-link" href="book-list.php">📚 Thư viện Mini</a></h1>
    <div class="user-actions">
        <span class="">Xin chào, <?= $username ?>!</span>
        <a class="logout-link" href="profile.php">Thông tin tài khoản</a>
        <a class="logout-link" href="book-list.php">Trang chủ</a>
        <a class="logout-link" href="php/logout.php">Đăng xuất</a>
    </div>
</header>

<main class="book-detail-page">
    <div class="book-detail-card">
        <div class="detail-thumb">
            <img src="<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($book['title']) ?>">
        </div>
        <div class="detail-info">
            <div class="detail-breadcrumb"><a href="book-list.php">← Quay lại danh sách sách</a></div>
            <h2><?= htmlspecialchars($book['title']) ?></h2>
            <div class="detail-meta">
                <span class="meta-chip">Tác giả: <?= htmlspecialchars($book['author'] ?? 'Chưa rõ') ?></span>
                <span class="meta-chip">Thể loại: <?= htmlspecialchars($book['category'] ?? 'Chưa rõ') ?></span>
                <span class="meta-chip status-chip <?= $statusClass ?>"><?= $statusText ?></span>
                <?php if (!empty($book['year'])): ?>
                    <span class="meta-chip">Năm XB: <?= (int)$book['year'] ?></span>
                <?php endif; ?>
                <?php if (!empty($book['publisher'])): ?>
                    <span class="meta-chip">NXB: <?= htmlspecialchars($book['publisher']) ?></span>
                <?php endif; ?>
                <?php if (!empty($book['language'])): ?>
                    <span class="meta-chip">Ngôn ngữ: <?= htmlspecialchars($book['language']) ?></span>
                <?php endif; ?>
                <?php if (!empty($book['pages'])): ?>
                    <span class="meta-chip">Số trang: <?= (int)$book['pages'] ?></span>
                <?php endif; ?>
            </div>
            <div class="detail-description">
                <h4>Mô tả</h4>
                <p><?= nl2br(htmlspecialchars($book['description'] ?: 'Chưa có mô tả cho sách này.')) ?></p>
            </div>
        </div>
    </div>

    <?php if ($msg === 'review_success'): ?>
        <div class="alert success" style="width:min(1080px,100%);">
            Cảm ơn bạn đã đánh giá!
        </div>
    <?php elseif ($msg === 'review_error'): ?>
        <div class="alert error" style="width:min(1080px,100%);">
            Không thể gửi đánh giá. Vui lòng thử lại.
        </div>
    <?php endif; ?>

    <div class="reviews-section">
        <div class="reviews-header">
            <h3>Đánh giá</h3>
            <form class="review-form" action="php/review_add.php" method="post">
                <input type="hidden" name="book_id" value="<?= (int)$book['id'] ?>">
                <label>Chọn sao
                    <select name="rating" required>
                        <option value="5">★★★★★ (5)</option>
                        <option value="4">★★★★☆ (4)</option>
                        <option value="3">★★★☆☆ (3)</option>
                        <option value="2">★★☆☆☆ (2)</option>
                        <option value="1">★☆☆☆☆ (1)</option>
                    </select>
                </label>
                <label>Nội dung
                    <textarea name="content" rows="3" placeholder="Chia sẻ cảm nhận của bạn..." required></textarea>
                </label>
                <button type="submit" class="btn primary sm">Gửi đánh giá</button>
            </form>
        </div>
        <?php if (empty($reviews)): ?>
            <p class="muted">Chưa có đánh giá nào cho sách này.</p>
        <?php else: ?>
            <div class="reviews-grid">
                <?php foreach ($reviews as $rv): ?>
                    <div class="review-card">
                        <div class="review-head">
                            <strong><?= htmlspecialchars($rv['username'] ?? 'Người dùng') ?></strong>
                            <span class="review-rating">
                                <?php
                                $stars = max(1, min(5, (int)$rv['rating']));
                                for ($i = 1; $i <= 5; $i++) {
                                    echo $i <= $stars ? '★' : '☆';
                                }
                                ?>
                            </span>
                        </div>
                        <p class="muted review-date"><?= htmlspecialchars($rv['date']) ?></p>
                        <p><?= nl2br(htmlspecialchars($rv['content'] ?? '')) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

</body>
</html>

