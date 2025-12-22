<!doctype html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="auth-page">
    <div class="auth-container">
        <h2>Đăng nhập Hệ thống</h2>
        
        <?php
        if (isset($_GET['error'])) {
            echo '<p class="alert error">' . htmlspecialchars($_GET['error']) . '</p>';
        }
        if (isset($_GET['success'])) {
            echo '<p class="alert success">' . htmlspecialchars($_GET['success']) . '</p>';
        }
        ?>

        <form action="php/login.php" method="post">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Mật khẩu" required>
            <button type="submit" class="btn primary">Đăng nhập</button>
        </form>

        <p><a href="register.php">Đăng ký tài khoản mới</a></p>
        <p><a href="forget.php">Quên mật khẩu?</a></p>
    </div>
</body>

</html>