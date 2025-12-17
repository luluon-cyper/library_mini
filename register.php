<!doctype html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký</title>
    <link rel="stylesheet" href="css/new_style.css">
</head>

<body class="auth-page">
    <div class="auth-container">
        <h2>Đăng ký Tài khoản</h2>
        
        <?php
        if (isset($_GET['error'])) {
            echo '<p class="alert error">' . htmlspecialchars($_GET['error']) . '</p>';
        }
        ?>

        <form action="php/register.php" method="post">
            <input type="text" name="username" placeholder="Họ Tên" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Mật khẩu" required>
            <button type="submit" class="btn primary">Đăng ký</button>
        </form>

        <p><a href="login.php">Đăng nhập</a></p>
    </div>
</body>

</html>