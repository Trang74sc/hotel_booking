<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liên Hệ - HotelLinker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        .page-header {
            background-image: url('assets/images/header.jpg');
            background-size: cover;
            background-position: center;
            padding: 100px 0;
            position: relative;
            color: white;
            text-align: center;
        }
        .page-header::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
        }
        .page-header h1 {
            position: relative;
            z-index: 1;
            font-size: 3rem;
        }
        .contact-section {
            padding: 60px 0;
            background-color: #f8f9fa;
        }
        .contact-info, .contact-form {
            background: #ffffff;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        .contact-info h5 {
            font-weight: bold;
            color: #0d6efd;
            margin-top: 20px;
        }
        .form-control {
            border-radius: 8px;
        }
        .btn-primary {
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 500;
        }
        .navbar {
            background-color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        .nav-link {
            color: #1f2937;
            font-weight: 500;
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
                    <li class="nav-item"><a class="nav-link active" href="lienhe.php">Liên Hệ</a></li>
                    <li class="nav-item"><a class="nav-link" href="gioithieu.php">Giới Thiệu</a></li>
                </ul>
                <div class="d-flex">
                    <a href="#" class="btn btn-outline-primary me-2">Đăng Nhập</a>
                    <a href="#" class="btn btn-primary">Đăng Ký</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Header -->
    <section class="page-header">
        <div class="container">
            <h1>LIÊN HỆ</h1>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section">
        <div class="container">
            <div class="row g-4">
                <!-- Thông tin liên hệ -->
                <div class="col-md-6">
                    <div class="contact-info">
                        <h4 class="mb-4 text-primary"><i class="bi bi-info-circle me-2"></i>Thông Tin Liên Hệ</h4>
                        <h5><i class="bi bi-geo-alt-fill me-2"></i>Địa chỉ:</h5>
                        <p>123 Trần Phú, Quận 5, TP. Hồ Chí Minh</p>

                        <h5><i class="bi bi-telephone-fill me-2"></i>Điện thoại:</h5>
                        <p>(+84) 28 1234 5678</p>

                        <h5><i class="bi bi-envelope-fill me-2"></i>Email:</h5>
                        <p>hotellinker@example.com</p>

                        <h5><i class="bi bi-clock-fill me-2"></i>Giờ làm việc:</h5>
                        <p>Thứ 2 - Chủ Nhật: 8:00 - 22:00</p>
                    </div>
                </div>

                <!-- Form liên hệ -->
                <div class="col-md-6">
                    <div class="contact-form">
                        <h4 class="mb-4 text-primary"><i class="bi bi-envelope-open me-2"></i>Gửi Thông Tin</h4>
                        <form method="post" action="#">
                            <div class="mb-3">
                                <label for="name" class="form-label">Họ và tên</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Nhập họ tên của bạn" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="example@email.com" required>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Nội dung</label>
                                <textarea class="form-control" id="message" name="message" rows="5" placeholder="Nội dung liên hệ..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Gửi liên hệ</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php require_once 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
