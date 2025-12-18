<?php
require 'config.php';
session_start();
$conn = getConn();

$is_admin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
$user_id = intval($_SESSION['user_id'] ?? 0);

function fetch_loans($conn, $is_admin, $user_id) {
    $base = "SELECT 
        pm.IDPhieuMuon AS id,
        pm.IDTaiKhoan AS user_id,
        pm.NgayMuon,
        pm.NgayHenTra,
        pm.TrangThaiMuonTra,
        tk.HoTen AS user_name
    FROM phieumuon pm
    LEFT JOIN taikhoan tk ON pm.IDTaiKhoan = tk.IDTaiKhoan ";

    if($is_admin){
        $base .= "ORDER BY pm.IDPhieuMuon DESC";
        $stmt = $conn->prepare($base);
    } else {
        $base .= "WHERE pm.IDTaiKhoan = ? ORDER BY pm.IDPhieuMuon DESC";
        $stmt = $conn->prepare($base);
        $stmt->bind_param('i', $user_id);
    }

    $stmt->execute();
    $res = $stmt->get_result();
    $loans = [];
    while($r = $res->fetch_assoc()) $loans[] = $r;
    $stmt->close();
    return $loans;
}

function fetch_items($conn, $loan_ids){
    if(empty($loan_ids)) return [];
    $in = implode(',', array_fill(0, count($loan_ids), '?'));
    $types = str_repeat('i', count($loan_ids));
    $sql = "SELECT 
        ct.IDPhieuMuon, ct.IDSach, ct.SoLuong, ct.NgayTra, ct.PhiPhat,
        s.TenSach
    FROM ct_phieumuon ct
    LEFT JOIN sach s ON ct.IDSach = s.IDSach
    WHERE ct.IDPhieuMuon IN ($in)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$loan_ids);
    $stmt->execute();
    $res = $stmt->get_result();
    $items = [];
    while($r = $res->fetch_assoc()){
        $items[$r['IDPhieuMuon']][] = $r;
    }
    $stmt->close();
    return $items;
}

$loans = fetch_loans($conn, $is_admin, $user_id);
$items = fetch_items($conn, array_column($loans, 'id'));
$conn->close();

header('Content-Type: application/json');
echo json_encode([
    'is_admin' => $is_admin,
    'loans' => $loans,
    'items' => $items
]);
exit;

