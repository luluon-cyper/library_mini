<?php
require 'admin_check.php';
require 'config.php';
$conn = getConn();

function getOrCreateID($conn, $table, $name_col, $name_value, $id_col) {
    $name_value = trim($name_value);
    if (empty($name_value)) return null;

    // 1. Tìm kiếm
    $stmt = $conn->prepare("SELECT {$id_col} FROM {$table} WHERE {$name_col} = ? LIMIT 1");
    $stmt->bind_param('s', $name_value);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
        $stmt->close();
        return $row[$id_col];
    }
    $stmt->close();

    // 2. Tạo mới nếu không tìm thấy
    $stmt = $conn->prepare("INSERT INTO {$table} ({$name_col}) VALUES (?)");
    $stmt->bind_param('s', $name_value);
    if (!$stmt->execute()) {
        return null;
    }
    $new_id = $conn->insert_id;
    $stmt->close();
    return $new_id;
}


$action = $_POST['action'] ?? '';

if ($action === 'add') {
    $title = $_POST['title'] ?? ''; 
    $author_name = $_POST['author'] ?? ''; 
    $category_name = $_POST['category'] ?? ''; 
    $image = trim($_POST['image'] ?? '');
    $quantity = max(0, intval($_POST['quantity'] ?? 0));
    $status = $quantity > 0 ? 'available' : 'borrowed'; 
    
    // Xử lý Tác giả và Thể loại để lấy ID
    $id_tacgia = getOrCreateID($conn, 'tacgia', 'TenTacGia', $author_name, 'IDTacGia');
    $id_theloai = getOrCreateID($conn, 'theloai', 'TenTheLoai', $category_name, 'IDTheLoai');
    
    // INSERT INTO sach (TenSach, IDTacGia, IDTheLoai, Anh, SoLuong, TinhTrang)
    $stmt = $conn->prepare('INSERT INTO sach (TenSach, IDTacGia, IDTheLoai, Anh, SoLuong, TinhTrang) VALUES (?, ?, ?, ?, ?, ?)'); 
    $stmt->bind_param('siisis', $title, $id_tacgia, $id_theloai, $image, $quantity, $status); 
    $stmt->execute();
    header('Location: ../admin_books.php');
    exit;
}

if ($action === 'delete') {
    $id = intval($_POST['id'] ?? 0); 
    $stmt = $conn->prepare('DELETE FROM sach WHERE IDSach = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    header('Location: ../admin_books.php');
    exit;
}

if ($action === 'edit') {
    $id = intval($_POST['id'] ?? 0); 
    $title = $_POST['title'] ?? ''; 
    $author_name = $_POST['author'] ?? ''; 
    $category_name = $_POST['category'] ?? ''; 
    $image = trim($_POST['image'] ?? '');
    $quantity = max(0, intval($_POST['quantity'] ?? 0));
    $status = $quantity > 0 ? 'available' : 'borrowed'; 

    // Xử lý Tác giả và Thể loại để lấy ID
    $id_tacgia = getOrCreateID($conn, 'tacgia', 'TenTacGia', $author_name, 'IDTacGia');
    $id_theloai = getOrCreateID($conn, 'theloai', 'TenTheLoai', $category_name, 'IDTheLoai');

    // UPDATE sach SET TenSach=?, IDTacGia=?, IDTheLoai=?, Anh=?, SoLuong=?, TinhTrang=? WHERE IDSach = ?
    $stmt = $conn->prepare('UPDATE sach SET TenSach=?, IDTacGia=?, IDTheLoai=?, Anh=?, SoLuong=?, TinhTrang=? WHERE IDSach = ?');
    $stmt->bind_param('siisisi', $title, $id_tacgia, $id_theloai, $image, $quantity, $status, $id); 
    $stmt->execute();
    header('Location: ../admin_books.php');
    exit;
}
?>