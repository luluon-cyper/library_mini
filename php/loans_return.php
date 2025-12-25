<?php
require 'admin_check.php';
require 'config.php';
$conf = require __DIR__ . '/loan_config.php';
$conn = getConn();
$conn->begin_transaction();

function fail($msg){
    global $conn;
    if($conn) $conn->rollback();
    header('Location: ../admin_loans.php?error=' . urlencode($msg));
    exit;
}

$loan_id = intval($_POST['loan_id'] ?? 0);
if($loan_id <= 0) fail('Thiếu ID phiếu mượn.');

$stmt = $conn->prepare("SELECT NgayHenTra, TrangThaiMuonTra FROM phieumuon WHERE IDPhieuMuon=? FOR UPDATE");
$stmt->bind_param('i', $loan_id);
$stmt->execute();
$stmt->bind_result($ngayHenTra, $status);
if(!$stmt->fetch()){
    $stmt->close();
    fail('Không tìm thấy phiếu.');
}
$stmt->close();

if($status !== 'dangmuon'){
    fail('Chỉ trả phiếu đang mượn.');
}

$today = new DateTime('today');
$due = new DateTime($ngayHenTra);
$overDays = max(0, $today->diff($due)->invert ? $today->diff($due)->days : 0);
$overFee = $overDays * intval($conf['OVERDUE_FEE']);

$stmt = $conn->prepare("SELECT IDSach, SoLuong FROM ct_phieumuon WHERE IDPhieuMuon=? FOR UPDATE");
$stmt->bind_param('i', $loan_id);
$stmt->execute();
$res = $stmt->get_result();
$details = [];
while($r = $res->fetch_assoc()){
    $details[] = $r;
}
$stmt->close();

foreach($details as $d){
    $stmt = $conn->prepare("UPDATE ct_phieumuon SET NgayTra=?, PhiPhat=? WHERE IDPhieuMuon=? AND IDSach=?");
    $todayStr = $today->format('Y-m-d');
    $fee = $overFee * $d['SoLuong'];
    $stmt->bind_param('siii', $todayStr, $fee, $loan_id, $d['IDSach']);
    if(!$stmt->execute()) fail('Lỗi cập nhật chi tiết.');
    $stmt->close();

    $stmt = $conn->prepare("UPDATE sach SET SoLuong = SoLuong + ? WHERE IDSach=?");
    $stmt->bind_param('ii', $d['SoLuong'], $d['IDSach']);
    if(!$stmt->execute()) fail('Lỗi hoàn số lượng.');
    $stmt->close();
}

$newStatus = $overDays > 0 ? 'quahan' : 'datra';
$stmt = $conn->prepare("UPDATE phieumuon SET TrangThaiMuonTra=? WHERE IDPhieuMuon=?");
$stmt->bind_param('si', $newStatus, $loan_id);
if(!$stmt->execute()) fail('Lỗi cập nhật phiếu.');
$stmt->close();

$conn->commit();
header('Location: ../admin_loans.php?success=' . urlencode('Đã cập nhật trả sách. Phí (nếu có) đã ghi nhận.'));
exit;

