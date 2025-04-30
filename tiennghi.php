<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiện Nghi - HotelLinker</title>
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
        .facility-card {
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 30px;
            background: white;
        }
        .facility-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .facility-info {
            padding: 20px;
        }
        .facility-info h5 {
            margin-bottom: 10px;
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
                    <li class="nav-item"><a class="nav-link active" href="tiennghi.php">Tiện Nghi</a></li>
                    <li class="nav-item"><a class="nav-link" href="lienhe.php">Liên Hệ</a></li>
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
            <h1>TIỆN NGHI</h1>
        </div>
    </section>

    <!-- Facilities Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <!-- Tiện nghi 1 -->
                <div class="col-md-4">
                    <div class="facility-card">
                        <img src="assets/images/hoboi.jpg" alt="Hồ bơi">
                        <div class="facility-info">
                            <h5><i class="bi bi-water me-2"></i>Hồ Bơi Ngoài Trời</h5>
                            <p>Thư giãn trong hồ bơi ngoài trời với không gian xanh mát và phục vụ nước miễn phí.</p>
                        </div>
                    </div>
                </div>

                <!-- Tiện nghi 2 -->
                <div class="col-md-4">
                    <div class="facility-card">
                        <img src="assets/images/gym.jpg" alt="Phòng gym">
                        <div class="facility-info">
                            <h5><i class="bi bi-heart-pulse me-2"></i>Phòng Gym Hiện Đại</h5>
                            <p>Trang bị máy móc hiện đại, không gian thoáng mát giúp bạn duy trì sức khỏe mỗi ngày.</p>
                        </div>
                    </div>
                </div>

                <!-- Tiện nghi 3 -->
                <div class="col-md-4">
                    <div class="facility-card">
                        <img src="assets/images/spa.jpg" alt="Dịch vụ spa">
                        <div class="facility-info">
                            <h5><i class="bi bi-flower1 me-2"></i>Spa & Massage</h5>
                            <p>Dịch vụ thư giãn đẳng cấp với đội ngũ nhân viên chuyên nghiệp và liệu pháp tự nhiên.</p>
                        </div>
                    </div>
                </div>

                <!-- Tiện nghi 4 -->
                <div class="col-md-4">
                    <div class="facility-card">
                        <img src="assets/images/nhahang.jpg" alt="Nhà hàng">
                        <div class="facility-info">
                            <h5><i class="bi bi-cup-straw me-2"></i>Nhà Hàng Ẩm Thực</h5>
                            <p>Thưởng thức các món ăn Á - Âu được chế biến bởi đầu bếp hàng đầu.</p>
                        </div>
                    </div>
                </div>

                <!-- Tiện nghi 5 -->
                <div class="col-md-4">
                    <div class="facility-card">
                        <img src="assets/images/doxe.jpg" alt="Bãi đỗ xe">
                        <div class="facility-info">
                            <h5><i class="bi bi-car-front me-2"></i>Bãi Đỗ Xe Miễn Phí</h5>
                            <p>Không gian rộng rãi, an ninh 24/7 dành cho xe ô tô và xe máy.</p>
                        </div>
                    </div>
                </div>

                <!-- Tiện nghi 6 -->
                <div class="col-md-4">
                    <div class="facility-card">
                        <img src="assets/images/wifi.jpg" alt="Wifi miễn phí">
                        <div class="facility-info">
                            <h5><i class="bi bi-wifi me-2"></i>Wifi Tốc Độ Cao</h5>
                            <p>Kết nối internet không giới hạn trong toàn bộ khuôn viên khách sạn.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <?php require_once 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
