<?php
require 'admin_check.php';
require 'config.php';
$conn = getConn();

$type = $_GET['type'] ?? 'loans';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="report_'.$type.'.csv"');

$out = fopen('php://output', 'w');
fputcsv($out, ['Loại báo cáo', $type]);

if($type === 'books'){
    fputcsv($out, ['ID', 'Tên sách', 'Tác giả', 'Thể loại', 'Số lượng', 'Tình trạng']);
    $sql = "SELECT s.IDSach, s.TenSach, tg.TenTacGia, tl.TenTheLoai, s.SoLuong, s.TinhTrang
            FROM sach s
            LEFT JOIN tacgia tg ON s.IDTacGia = tg.IDTacGia
            LEFT JOIN theloai tl ON s.IDTheLoai = tl.IDTheLoai
            ORDER BY s.IDSach";
    $res = $conn->query($sql);
    while($r = $res->fetch_assoc()){
        fputcsv($out, $r);
    }
} else {
    fputcsv($out, ['ID phiếu', 'ID User', 'Tên User', 'Ngày mượn', 'Hạn trả', 'Trạng thái', 'ID sách', 'Số lượng', 'Phi phạt', 'Ngày trả']);
    $sql = "SELECT pm.IDPhieuMuon, pm.IDTaiKhoan, tk.HoTen AS TenUser,
                   pm.NgayMuon, pm.NgayHenTra, pm.TrangThaiMuonTra,
                   ct.IDSach, ct.SoLuong, ct.PhiPhat, ct.NgayTra
            FROM phieumuon pm
            JOIN ct_phieumuon ct ON pm.IDPhieuMuon = ct.IDPhieuMuon
            LEFT JOIN taikhoan tk ON pm.IDTaiKhoan = tk.IDTaiKhoan
            ORDER BY pm.IDPhieuMuon DESC";
    $res = $conn->query($sql);
    while($r = $res->fetch_assoc()){
        fputcsv($out, $r);
    }
}

fclose($out);
exit;
?>

