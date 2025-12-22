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

$stmt = $conn->prepare("SELECT NgayMuon, NgayHenTra, TrangThaiMuonTra FROM phieumuon WHERE IDPhieuMuon=? FOR UPDATE");
$stmt->bind_param('i', $loan_id);
$stmt->execute();
$stmt->bind_result($ngayMuon, $ngayHenTra, $status);
if(!$stmt->fetch()){
    $stmt->close();
    fail('Không tìm thấy phiếu.');
}
$stmt->close();

if($status !== 'dangmuon'){
    fail('Chỉ gia hạn phiếu đang mượn.');
}

$ngayMuonDt = new DateTime($ngayMuon);
$ngayHenTraDt = new DateTime($ngayHenTra);
$newDue = (clone $ngayHenTraDt)->modify('+' . intval($conf['EXTEND_DAYS']) . ' days');

$maxDue = (clone $ngayMuonDt)->modify('+' . intval($conf['MAX_DAYS']) . ' days');
if($newDue > $maxDue) $newDue = $maxDue;

if($newDue <= $ngayHenTraDt){
    fail('Đã đạt hạn tối đa, không thể gia hạn thêm.');
}

$newDueStr = $newDue->format('Y-m-d');
$stmt = $conn->prepare("UPDATE phieumuon SET NgayHenTra=? WHERE IDPhieuMuon=?");
$stmt->bind_param('si', $newDueStr, $loan_id);
if(!$stmt->execute()) fail('Lỗi gia hạn.');
$stmt->close();

$conn->commit();
header('Location: ../admin_loans.php?success=' . urlencode('Gia hạn thành công.'));
exit;

