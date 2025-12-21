<?php
require 'auth_check.php';
require 'config.php';

$book_id = intval($_POST['book_id'] ?? 0);
$rating = intval($_POST['rating'] ?? 0);
$content = trim($_POST['content'] ?? '');
$user_id = intval($_SESSION['user_id'] ?? 0);

if ($book_id <= 0 || $rating < 1 || $rating > 5 || $user_id <= 0 || $content === '') {
    header('Location: ../book-detail.php?id=' . $book_id . '&msg=review_error');
    exit;
}

$conn = getConn();

$stmt = $conn->prepare("INSERT INTO danhgia (IDSach, IDTaiKhoan, SoSao, NoiDungDanhGia) VALUES (?, ?, ?, ?)");
$stmt->bind_param('iiis', $book_id, $user_id, $rating, $content);
if ($stmt->execute()) {
    $stmt->close();
    $conn->close();
    header('Location: ../book-detail.php?id=' . $book_id . '&msg=review_success');
    exit;
}

$stmt->close();
$conn->close();
header('Location: ../book-detail.php?id=' . $book_id . '&msg=review_error');
exit;

