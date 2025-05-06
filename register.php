<?php
session_start();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Đăng ký tài khoản HotelLinker để đặt phòng khách sạn sang trọng tại Hà Nội với các tiện nghi đẳng cấp.">
    <title>Đăng Ký - HotelLinker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        /* General Styles */
        body {
            font-family: 'Roboto', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: linear-gradient(135deg, #ffffff, #e0e7ff);
            overflow-x: hidden;
            margin: 0;
        }

        /* Navbar */
        .navbar {
            background: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 15px 0;
        }
        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            color: #111827;
        }
        .nav-link {
            font-weight: 500;
            color: #4b5563;
            transition: color 0.3s ease;
        }
        .nav-link:hover, .nav-link.active {
            color: #d4af37;
        }

        /* Register Section */
        .register-section {
            flex-grow: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 60px 20px; /* Khoảng cách với navbar và footer */
        }
        .register-card {
            max-width: 450px;
            width: 100%;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
            padding: 30px;
            border: 1px solid #e5e7eb;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .register-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.2);
        }
        .register-card h2 {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            color: #111827;
            margin-bottom: 20px;
            text-align: center;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.1);
        }
        .register-card .form-label {
            font-weight: 500;
            color: #111827;
            font-size: 0.9rem;
        }
        .register-card .form-control {
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            padding: 10px;
            font-size: 0.9rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        .register-card .form-control:focus {
            border-color: #d4af37;
            box-shadow: 0 0 8px rgba(212, 175, 55, 0.2);
        }
        .register-card .btn-primary {
            background: #d4af37;
            border: none;
            padding: 10px;
            font-weight: 600;
            border-radius: 8px;
            transition: background 0.3s ease, transform 0.3s ease;
            width: 100%;
            font-size: 0.9rem;
        }
        .register-card .btn-primary:hover {
            background: #b8972f;
            transform: translateY(-3px);
        }
        .register-card .btn-outline-dark {
            border-color: #4b5563;
            color: #4b5563;
            border-radius: 8px;
            padding: 8px 20px;
            transition: all 0.3s ease;
            font-size: 0.85rem;
        }
        .register-card .btn-outline-dark:hover {
            background: #4b5563;
            color: #fff;
            transform: translateY(-3px);
        }
        .register-card a {
            color: #d4af37;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
        }
        .register-card a:hover {
            text-decoration: underline;
        }
        .register-card .text-center {
            font-size: 0.9rem;
        }

        /* Footer */
        footer {
            margin-top: auto; /* Đẩy footer xuống cuối trang */
        }

        /* Container */
        .container {
            max-width: 1200px;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .navbar-brand {
                font-size: 1.5rem;
            }
            .register-section {
                padding: 40px 15px; /* Giảm padding trên mobile */
            }
            .register-card {
                padding: 20px;
                max-width: 90%;
            }
            .register-card h2 {
                font-size: 1.5rem;
            }
            .register-card .form-control {
                padding: 8px;
                font-size: 0.85rem;
            }
            .register-card .btn-primary {
                padding: 8px;
                font-size: 0.85rem;
            }
            .register-card .btn-outline-dark {
                padding: 6px 15px;
                font-size: 0.8rem;
            }
            .register-card .form-label, .register-card a, .register-card .text-center {
                font-size: 0.85rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="index.php">HotelLinker</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Trang Chủ</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Phòng</a></li>
                    <li class="nav-item"><a class="nav-link" href="tiennghi.php">Tiện Nghi</a></li>
                    <li class="nav-item"><a class="nav-link" href="lienhe.php">Liên Hệ</a></li>
                    <li class="nav-item"><a class="nav-link" href="gioithieu.php">Giới Thiệu</a></li>
                </ul>
                <div class="d-flex">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <span class="me-3">Xin chào, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                        <a href="logout.php" class="btn btn-outline-danger">Đăng Xuất</a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-outline-primary me-2">Đăng Nhập</a>
                        <a href="register.php" class="btn btn-primary active">Đăng Ký</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Register Section -->
    <section class="register-section">
        <div class="register-card" data-aos="fade-up" data-aos-duration="1000">
            <h2>📝 Đăng Ký Tài Khoản</h2>
            <?php if (!empty($_SESSION['register_error'])): ?>
                <div class="alert alert-danger text-center">
                    <?= htmlspecialchars($_SESSION['register_error']) ?>
                </div>
                <?php unset($_SESSION['register_error']); ?>
            <?php endif; ?>

            <?php if (!empty($_SESSION['register_success'])): ?>
                <div class="alert alert-success text-center">
                    <?= htmlspecialchars($_SESSION['register_success']) ?>
                </div>
                <?php unset($_SESSION['register_success']); ?>
            <?php endif; ?>

            <form action="xulyregister.php" method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">Họ và Tên</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Nguyễn Văn A..." required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="email@example.com" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label">Mật Khẩu</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Mật khẩu mạnh..." required>
                </div>
                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-primary">Tạo Tài Khoản</button>
                </div>
                <div class="text-center">
                    <a href="login.php">Đã có tài khoản? <strong>Đăng nhập</strong></a>
                </div>
                <div class="text-center mt-3">
                    <a href="index.php" class="btn btn-outline-dark">Quay lại Trang chủ</a>
                </div>
            </form>
        </div>
    </section>

    <?php require_once 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({
            once: true,
            offset: 100
        });
    </script>
</body>
</html>