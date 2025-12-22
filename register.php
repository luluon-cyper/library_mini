<!doctype html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký</title>
    <link rel="stylesheet" href="css/style.css?v=2">
</head>

<body class="auth-page">
    <div class="auth-container">
        <h2>Đăng ký Tài khoản</h2>
        <p style="margin:6px 0 18px; color:#6b7280;">Tạo tài khoản để bắt đầu mượn sách.</p>

        <?php
        if (isset($_GET['error'])) {
            echo '<p class="alert error">' . htmlspecialchars($_GET['error']) . '</p>';
        }
        ?>

        <form action="php/register.php" method="post" style="display:flex;flex-direction:column;gap:12px;">
            <input type="text" name="username" placeholder="Họ tên đầy đủ" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Mật khẩu" required>
            <button type="submit" class="btn primary" style="width:100%;">Đăng ký</button>
        </form>

        <p class="muted" style="margin-top:14px;">Đã có tài khoản? <a href="login.php">Đăng nhập</a></p>
    </div>
</body>

</html>