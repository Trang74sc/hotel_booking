<?php session_start(); ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng Ký</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #ffecd2,rgb(255, 255, 255));
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 0.95rem;
        }
        .register-card {
            width: 100%;
            max-width: 600px;
            background: #fff;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
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

<div class="register-card">
    <h2 class="text-center mb-4">📝 Đăng Ký Tài Khoản</h2>
    <form action="xulyregister.php" method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Họ và tên:</label>
            <input type="text" class="form-control form-control-lg" id="name" name="name" placeholder="Nguyễn Văn A..." required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder="email@example.com" required>
        </div>
        <div class="mb-4">
            <label for="password" class="form-label">Mật khẩu:</label>
            <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="Mật khẩu mạnh..." required>
        </div>
        <div class="d-grid mb-3">
            <button type="submit" class="btn btn-success btn-lg">Tạo Tài Khoản</button>
        </div>
        <div class="text-center">
            <a href="login.php">Đã có tài khoản? <strong>Đăng nhập</strong></a>
        </div>
        <div class="text-center mt-3">
            <a href="index.php" class="btn btn-outline-dark btn-sm">Quay lại Trang chủ</a>
        </div>
    </form>
</div>

</body>
</html>
