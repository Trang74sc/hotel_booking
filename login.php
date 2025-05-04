<?php session_start(); ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng Nhập</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        background: linear-gradient(135deg,rgb(255, 255, 255), #acb6e5);
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 0.95rem; /* nhỏ hơn mặc định */
    }
    .login-card {
        width: 100%;
        max-width: 500px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        padding: 40px;
        font-size: 0.95rem;
    }
    .form-control {
        font-size: 0.95rem;
    }
    .btn {
        font-size: 0.95rem;
    }
</style>

</head>
<body>

<div class="login-card">
    <h2 class="text-center mb-4">🔐 Đăng Nhập</h2>
    <form action="xulylogin.php" method="POST">
        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder="Nhập email..." required>
        </div>
        <div class="mb-4">
            <label for="password" class="form-label">Mật khẩu:</label>
            <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="Nhập mật khẩu..." required>
        </div>
        <div class="d-grid mb-3">
            <button type="submit" class="btn btn-primary btn-lg">Đăng Nhập</button>
        </div>
        <div class="text-center">
            <a href="register.php">Chưa có tài khoản? <strong>Đăng ký</strong></a>
        </div>
        <div class="text-center mt-3">
            <a href="index.php" class="btn btn-outline-dark btn-sm">Quay lại Trang chủ</a>
        </div>
    </form>
</div>

</body>
</html>
