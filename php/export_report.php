<?php
require 'admin_check.php';
require 'config.php';
$conn = getConn();

interface ReportAdapter {
    public function filename(): string;
    public function headerRow(): array;
    public function rows(mysqli $conn): iterable;
}

class BooksReportAdapter implements ReportAdapter {
    public function filename(): string { return 'report_books.csv'; }
    public function headerRow(): array {
        return ['ID', 'Tên sách', 'Tác giả', 'Thể loại', 'Số lượng', 'Tình trạng'];
    }
    public function rows(mysqli $conn): iterable {
        $sql = "SELECT s.IDSach, s.TenSach, tg.TenTacGia, tl.TenTheLoai, s.SoLuong, s.TinhTrang
                FROM sach s
                LEFT JOIN tacgia tg ON s.IDTacGia = tg.IDTacGia
                LEFT JOIN theloai tl ON s.IDTheLoai = tl.IDTheLoai
                ORDER BY s.IDSach";
        $res = $conn->query($sql);
        while ($r = $res->fetch_assoc()) {
            yield $r;
        }
    }
}

class LoansReportAdapter implements ReportAdapter {
    public function filename(): string { return 'report_loans.csv'; }
    public function headerRow(): array {
        return ['ID phiếu', 'ID User', 'Tên User', 'Ngày mượn', 'Hạn trả', 'Trạng thái', 'ID sách', 'Số lượng', 'Phi phạt', 'Ngày trả'];
    }
    public function rows(mysqli $conn): iterable {
        $sql = "SELECT pm.IDPhieuMuon, pm.IDTaiKhoan, tk.HoTen AS TenUser,
                       pm.NgayMuon, pm.NgayHenTra, pm.TrangThaiMuonTra,
                       ct.IDSach, ct.SoLuong, ct.PhiPhat, ct.NgayTra
                FROM phieumuon pm
                JOIN ct_phieumuon ct ON pm.IDPhieuMuon = ct.IDPhieuMuon
                LEFT JOIN taikhoan tk ON pm.IDTaiKhoan = tk.IDTaiKhoan
                ORDER BY pm.IDPhieuMuon DESC";
        $res = $conn->query($sql);
        while ($r = $res->fetch_assoc()) {
            yield $r;
        }
    }
}
$type = $_GET['type'] ?? 'loans';
$adapter = ($type === 'books') ? new BooksReportAdapter() : new LoansReportAdapter();

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="'.$adapter->filename().'"');

$out = fopen('php://output', 'w');
fputcsv($out, ['Loại báo cáo', $type]);
fputcsv($out, $adapter->headerRow());
foreach ($adapter->rows($conn) as $row) {
    fputcsv($out, $row);
}
fclose($out);
exit;
?>

