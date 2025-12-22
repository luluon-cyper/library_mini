<?php
require 'php/auth_check.php';
$username = htmlspecialchars($_SESSION['username'] ?? 'Người dùng'); 
?>
<!doctype html>
<html lang="vi">

<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Thư viện Sách</title>
  <link rel="stylesheet" href="css/style.css?v=2"/>
</head>

<body>

<header class="app-header user-header">
  <h1 class="logo"><a class="logo-link" href="book-list.php">📚 Thư viện Mini</a></h1>
  
  <div class="search-bar-header">
    <input type="text" id="searchInput" placeholder="Tìm kiếm sách theo tên..." />
    <button id="searchBtn">Tìm kiếm</button>
  </div>

  <div class="user-actions">
    <span class="">Xin chào, <?= $username ?>!</span>
    <a class="logout-link" href="profile.php">Thông tin tài khoản</a>
    <a class="logout-link" href="user_loans.php">Phiếu mượn</a>
    <a class="logout-link" href="php/logout.php">Đăng xuất</a>
  </div>
</header>

<main class="book-container">
  <div id="booksContainer">
    <p>Đang tải danh sách sách...</p>
  </div>
</main>

<script src="js/app.js?v=2"></script> 

</body>
</html>