<?php
header('Content-Type: application/json; charset=utf-8');
require 'config.php';
$conn = getConn();

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Thiếu mã sách'], JSON_UNESCAPED_UNICODE);
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

if ($res->num_rows === 0) {
    http_response_code(404);
    echo json_encode(['error' => 'Không tìm thấy sách'], JSON_UNESCAPED_UNICODE);
    exit;
}

$row = $res->fetch_assoc();
$fallback_img = 'https://dayve.vn/wp-content/uploads/2022/11/Ve-quyen-sach-Buoc-16.jpg';
if (!isset($row['image']) || !$row['image']) {
    $row['image'] = $fallback_img;
}

echo json_encode($row, JSON_UNESCAPED_UNICODE);
?>

