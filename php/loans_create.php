<?php
require 'admin_check.php';
require 'config.php';
$conf = require __DIR__ . '/loan_config.php';

$conn = getConn();
$conn->begin_transaction();

function fail($msg) {
    global $conn;
    if($conn) $conn->rollback();
    header('Location: ../admin_loans.php?error=' . urlencode($msg));
    exit;
}

$user_id = intval($_POST['user_id'] ?? 0);
$items_raw = trim($_POST['items'] ?? '');
if($user_id <= 0 || $items_raw === '') {
    fail('Thiếu thông tin người mượn hoặc sách.');
}

$lines = array_filter(array_map('trim', explode("\n", $items_raw)));
$items = [];
foreach($lines as $line){
    [$bid, $qty] = array_pad(array_map('trim', explode(',', $line)), 2, null);
    $bid = intval($bid);
    $qty = max(0, intval($qty));
    if($bid > 0 && $qty > 0){
        $items[] = ['id' => $bid, 'qty' => $qty];
    }
}
if(empty($items)) {
    fail('Không có sách hợp lệ.');
}

// Kiểm tra tồn kho
foreach($items as $it){
    $stmt = $conn->prepare("SELECT SoLuong FROM sach WHERE IDSach=? FOR UPDATE");
    $stmt->bind_param('i', $it['id']);
    $stmt->execute();
    $stmt->bind_result($stock);
    if(!$stmt->fetch()) {
        $stmt->close();
        fail("Sách ID {$it['id']} không tồn tại.");
    }
    $stmt->close();
    if($stock < $it['qty']) {
        fail("Sách ID {$it['id']} không đủ số lượng (còn $stock).");
    }
}

// Tính hạn trả
$today = new DateTime('today');
$due = (clone $today)->modify('+' . intval($conf['DEFAULT_DAYS']) . ' days');
$todayStr = $today->format('Y-m-d');
$dueStr = $due->format('Y-m-d');

// Tạo phiếu mượn
$stmt = $conn->prepare("INSERT INTO phieumuon (IDTaiKhoan, NgayMuon, NgayHenTra, TrangThaiMuonTra) VALUES (?, ?, ?, 'dangmuon')");
$stmt->bind_param('iss', $user_id, $todayStr, $dueStr);
if(!$stmt->execute()) fail('Lỗi tạo phiếu mượn.');
$loan_id = $conn->insert_id;
$stmt->close();

// Thêm chi tiết + trừ kho
foreach($items as $it){
    $stmt = $conn->prepare("INSERT INTO ct_phieumuon (IDPhieuMuon, IDSach, SoLuong, PhiPhat) VALUES (?, ?, ?, 0)");
    $stmt->bind_param('iii', $loan_id, $it['id'], $it['qty']);
    if(!$stmt->execute()) fail('Lỗi thêm chi tiết phiếu.');
    $stmt->close();

    $stmt = $conn->prepare("UPDATE sach SET SoLuong = SoLuong - ? WHERE IDSach = ?");
    $stmt->bind_param('ii', $it['qty'], $it['id']);
    if(!$stmt->execute()) fail('Lỗi trừ số lượng.');
    $stmt->close();
}

$conn->commit();
header('Location: ../admin_loans.php?success=' . urlencode('Tạo phiếu mượn thành công.'));
exit;

