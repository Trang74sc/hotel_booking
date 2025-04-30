<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giới Thiệu - HotelLinker</title>
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
        .intro-section {
            padding: 60px 0;
            background-color: #f9f9f9;
        }
        .intro-text {
            font-size: 1.1rem;
            line-height: 1.8;
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
                    <li class="nav-item"><a class="nav-link" href="lienhe.php">Liên Hệ</a></li>
                    <li class="nav-item"><a class="nav-link active" href="gioithieu.php">Giới Thiệu</a></li>
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
            <h1>GIỚI THIỆU</h1>
        </div>
    </section>

   <!-- Introduction Section -->
   <section class="intro-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 order-md-2">
                    <img src="assets/images/header.jpg" alt="Giới thiệu khách sạn" class="img-fluid rounded shadow intro-image">
                </div>
                <div class="col-md-6 mt-4 mt-md-0 order-md-1">
                    <div class="intro-text">
                        <h2 class="mb-4 fw-bold">Chào Mừng Đến Với HotelLinker</h2>
                        <p><strong>HotelLinker</strong> tự hào là điểm đến hoàn hảo cho những ai tìm kiếm sự sang trọng, tiện nghi và dịch vụ đẳng cấp. Tọa lạc tại vị trí đắc địa, khách sạn của chúng tôi mang đến không gian lưu trú tinh tế với thiết kế hiện đại và không gian ấm cúng.</p>
                        <p>Với đội ngũ nhân viên chuyên nghiệp và tận tâm, chúng tôi cam kết mang lại trải nghiệm đáng nhớ, từ những phòng nghỉ được chăm chút tỉ mỉ đến các tiện ích đa dạng như nhà hàng ẩm thực cao cấp, spa thư giãn và không gian sự kiện linh hoạt.</p>
                        <p>Hãy để <strong>HotelLinker</strong> trở thành ngôi nhà thứ hai của bạn, nơi mỗi khoảnh khắc đều trở nên đặc biệt!</p>
                        <a href="lienhe.php" class="btn btn-primary mt-3">Liên Hệ Ngay</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php require_once 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
