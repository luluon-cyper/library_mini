<?php
require 'admin_check.php';
require 'config.php';
$conn = getConn();

$borrowed_books  = (int)$conn->query("
    SELECT COALESCE(SUM(ct.SoLuong),0)
    FROM phieumuon pm
    JOIN ct_phieumuon ct ON pm.IDPhieuMuon = ct.IDPhieuMuon
    WHERE pm.TrangThaiMuonTra = 'dangmuon'
")->fetch_row()[0];
$available_books = (int)$conn->query("SELECT COALESCE(SUM(SoLuong),0) FROM sach")->fetch_row()[0];
$total_copies = $available_books + $borrowed_books;

$loan_total   = (int)$conn->query("SELECT COUNT(*) FROM phieumuon")->fetch_row()[0];
$loan_open    = (int)$conn->query("SELECT COUNT(*) FROM phieumuon WHERE TrangThaiMuonTra='dangmuon'")->fetch_row()[0];
$loan_closed  = (int)$conn->query("SELECT COUNT(*) FROM phieumuon WHERE TrangThaiMuonTra='datra'")->fetch_row()[0];
$loan_overdue = (int)$conn->query("SELECT COUNT(*) FROM phieumuon WHERE TrangThaiMuonTra='quahan'")->fetch_row()[0];

$genre_sql = "
    SELECT tl.TenTheLoai AS category, COUNT(*) AS total
    FROM phieumuon pm
    JOIN ct_phieumuon ct ON pm.IDPhieuMuon = ct.IDPhieuMuon
    JOIN sach s ON ct.IDSach = s.IDSach
    LEFT JOIN theloai tl ON s.IDTheLoai = tl.IDTheLoai
    GROUP BY tl.TenTheLoai
    ORDER BY total DESC
    LIMIT 5
";
$genre_res = $conn->query($genre_sql);
$top_genres = [];
while($r = $genre_res->fetch_assoc()) $top_genres[] = $r;

$fee_sql = "SELECT SUM(PhiPhat) FROM ct_phieumuon";
$fee_total = (int)($conn->query($fee_sql)->fetch_row()[0] ?? 0);

$conn->close();

header('Content-Type: application/json; charset=utf-8');
echo json_encode([
    'books' => [
        'total' => $total_copies,
        'available' => $available_books,
        'borrowed' => $borrowed_books,
    ],
    'loans' => [
        'total' => $loan_total,
        'open' => $loan_open,
        'closed' => $loan_closed,
        'overdue' => $loan_overdue,
    ],
    'fees' => [
        'total' => $fee_total,
    ],
    'top_genres' => $top_genres,
], JSON_UNESCAPED_UNICODE);
?>

