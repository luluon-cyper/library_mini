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
  <link rel="stylesheet" href="css/style.css?v=4"/>
</head>
<body>

<header class="app-header user-header">
  <h1 class="logo"><a class="logo-link" href="book-list.php">📚 Thư viện Mini</a></h1>
  
  <div class="search-hero">
    <div class="search-pill">
      <div class="search-type-wrap">
        <button class="search-type-btn" id="searchTypeBtn" type="button">
          <span class="grid-icon">
            <svg viewBox="0 0 24 24" width="18" height="18"><path fill="currentColor" d="M4 8h4V4H4v4zm6 12h4v-4h-4v4zm-6 0h4v-4H4v4zm0-6h4v-4H4v4zm6 0h4v-4h-4v4zm6-10v4h4V4h-4zm-6 4h4V4h-4v4zm6 6h4v-4h-4v4zm0 6h4v-4h-4v4z"></path></svg>
          </span>
          <span id="searchTypeLabel">Nhan đề</span>
          <span class="caret">▼</span>
        </button>
        <ul class="search-type-menu" id="searchTypeMenu">
          <li data-type="title" data-placeholder="Nhập nhan đề...">Nhan đề</li>
          <li data-type="author" data-placeholder="Nhập tên tác giả...">Tác giả</li>
          <li data-type="category" data-placeholder="Nhập thể loại...">Thể loại</li>
        </ul>
      </div>

      <div class="search-input-group">
        <input type="text" id="searchInput" placeholder="Nhập nhan đề..." />
        <div class="search-actions">
           <button id="searchBtn" class="search-icon-btn" type="button">🔍</button>
        </div>
      </div>
    </div>
  </div>

  <div class="user-actions">
    <span>Xin chào, <?= $username ?>!</span>
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

<script src="js/app.js?v=4"></script> 
</body>
</html>