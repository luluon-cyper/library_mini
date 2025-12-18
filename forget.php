<!doctype html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên Mật Khẩu</title>
    <link rel="stylesheet" href="css/new_style.css">
</head>

<body class="auth-page">
    <div class="auth-container">
        <h2>Quên Mật Khẩu</h2>
        
        <div id="errorMessage" class="alert" style="display: none;"></div>

        <form id="forgetForm" action="php/forget_action.php" method="post">
            <input type="email" name="email" placeholder="Nhập Email đã đăng ký" required>
            <button type="submit" class="btn primary">Gửi yêu cầu đặt lại</button>
        </form>

        <p><a href="login.php">Quay lại Đăng nhập</a></p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('forgetForm');
            const errorMessageDiv = document.getElementById('errorMessage');

            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                errorMessageDiv.style.display = 'none';
                errorMessageDiv.textContent = '';
                errorMessageDiv.classList.remove('error', 'success');
                
                const formData = new FormData(form);
                
                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        body: formData
                    });
                    
                    const result = await response.json();
                    
                    const isSuccess = result.status === 'success';
                    errorMessageDiv.classList.add(isSuccess ? 'success' : 'error');
                    errorMessageDiv.textContent = result.message;
                    errorMessageDiv.style.display = 'block';

                } catch (error) {
                    errorMessageDiv.classList.add('error');
                    errorMessageDiv.textContent = 'Lỗi kết nối máy chủ.';
                    errorMessageDiv.style.display = 'block';
                }
            });
        });
    </script>
</body>

</html>