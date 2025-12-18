<?php
header('Content-Type: application/json; charset=utf-8');
require 'config.php';
$conn = getConn();

$keyword = trim($_GET['keyword'] ?? '');

// SỬA: Base Query với JOIN để lấy tên Tác giả/Thể loại và AS để đổi tên cột
$base_query = "SELECT 
    s.IDSach AS id, 
    s.TenSach AS title, 
    s.Anh AS image,
    tg.TenTacGia AS author, 
    tl.TenTheLoai AS category, 
    s.TinhTrang AS status 
FROM sach s
LEFT JOIN tacgia tg ON s.IDTacGia = tg.IDTacGia
LEFT JOIN theloai tl ON s.IDTheLoai = tl.IDTheLoai";

if($keyword === ''){
    $stmt = $conn->prepare($base_query . ' ORDER BY s.IDSach DESC');
} else {
    $like = '%' . $keyword . '%';
    // SỬA: Tìm kiếm theo TenSach
    $stmt = $conn->prepare($base_query . ' WHERE s.TenSach LIKE ? ORDER BY s.IDSach DESC');
    $stmt->bind_param('s', $like);
}
$stmt->execute();
$res = $stmt->get_result();
$books = [];
$fallback_img = 'https://dayve.vn/wp-content/uploads/2022/11/Ve-quyen-sach-Buoc-16.jpg';
while($r = $res->fetch_assoc()) {
    if(!isset($r['image']) || !$r['image']){
        $r['image'] = $fallback_img;
    }
    $books[] = $r;
}
echo json_encode($books, JSON_UNESCAPED_UNICODE);
?>