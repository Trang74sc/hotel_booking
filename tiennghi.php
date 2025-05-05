<?php
require_once 'config.php';
session_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiện Nghi - HotelLinker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        /* General Styles */
        body {
            overflow-x: hidden;
        }

        /* Page Header (Hero Section) */
        .page-header {
            position: relative;
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.5)), url('assets/images/header.jpg') no-repeat center/cover;
            height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: #fff;
            margin-bottom: 0;
        }
        .page-header .hero-content {
            position: relative;
            z-index: 1;
        }
        .page-header h1 {
            font-size: 4.5rem;
            font-weight: 700;
            font-family: 'Playfair Display', serif;
            letter-spacing: 2px;
            text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.5);
            margin-bottom: 20px;
        }
        .page-header p {
            font-size: 1.2rem;
            font-family: 'Roboto', sans-serif;
            max-width: 600px;
            margin: 0 auto 30px;
        }
        .page-header .btn-primary {
            background: #d4af37;
            border: none;
            padding: 12px 30px;
            font-family: 'Roboto', sans-serif;
            font-weight: 600;
            border-radius: 8px;
            transition: background 0.3s ease, transform 0.3s ease;
        }
        .page-header .btn-primary:hover {
            background: #b8972f;
            transform: translateY(-3px);
        }

        /* Facilities Section */
        .facilities-section {
            padding: 80px 0;
            background: #fff;
        }
        .facilities-section h2 {
            font-size: 3rem;
            font-weight: 700;
            font-family: 'Playfair Display', serif;
            color: #111827;
            text-align: center;
            margin-bottom: 30px;
        }
        .facilities-section p.lead {
            font-size: 1.2rem;
            color: #4b5563;
            text-align: center;
            max-width: 800px;
            margin: 0 auto 50px;
            font-family: 'Roboto', sans-serif;
        }
        .facility-card {
            background: #fff;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid #e5e7eb;
        }
        .facility-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            border-color: #d4af37;
        }
        .facility-card img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        .facility-card:hover img {
            transform: scale(1.1);
        }
        .facility-info {
            padding: 30px;
            background: #fff;
        }
        .facility-info h5 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #111827;
            font-family: 'Roboto', sans-serif;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }
        .facility-info h5 i {
            font-size: 2rem;
            color: #000000;
            margin-right: 15px;
            transition: transform 0.3s ease;
        }
        .facility-card:hover h5 i {
            transform: rotate(10deg);
        }
        .facility-info p {
            font-size: 1rem;
            color: #4b5563;
            line-height: 1.6;
            font-family: 'Roboto', sans-serif;
        }

        /* Container */
        .container {
            max-width: 1200px;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .page-header {
                height: 60vh;
                background-attachment: scroll;
            }
            .page-header h1 {
                font-size: 2.5rem;
            }
            .page-header p {
                font-size: 1rem;
            }
            .facilities-section {
                padding: 40px 0;
            }
            .facilities-section h2 {
                font-size: 2rem;
            }
            .facility-card img {
                height: 200px;
            }
            .facility-info {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar (Unchanged) -->
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
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <span class="me-3">Xin chào, <?php echo $_SESSION['user_name']; ?></span>
                        <a href="logout.php" class="btn btn-outline-danger">Đăng Xuất</a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-outline-primary me-2">Đăng Nhập</a>
                        <a href="register.php" class="btn btn-primary">Đăng Ký</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="page-header">
        <div class="hero-content" data-aos="fade-up" data-aos-duration="1000">
            <h1>TIỆN NGHI ĐẲNG CẤP</h1>
            <p>Khám phá các tiện nghi hiện đại và dịch vụ sang trọng tại HotelLinker, mang đến trải nghiệm lưu trú hoàn hảo.</p>
            <a href="booking.php" class="btn btn-primary">Đặt Phòng Ngay</a>
        </div>
    </section>

    <!-- Facilities Section -->
    <section class="facilities-section">
        <div class="container">
            <h2 data-aos="fade-up" data-aos-duration="1000">Tiện Nghi Của HotelLinker</h2>
            <p class="lead" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="100">
                Tận hưởng những tiện nghi được thiết kế để mang lại sự thoải mái và tiện lợi tối đa, từ hồ bơi ngoài trời đến dịch vụ spa thư giãn.
            </p>
            <div class="row">
                <!-- Tiện nghi 1 -->
                <div class="col-md-4" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="100">
                    <div class="facility-card">
                        <img src="assets/images/hoboi.jpg" alt="Hồ bơi ngoài trời" aria-label="Hình ảnh hồ bơi ngoài trời">
                        <div class="facility-info">
                            <h5><i class="bi bi-water"></i>Hồ Bơi Ngoài Trời</h5>
                            <p>Thư giãn trong hồ bơi với không gian xanh mát, kèm dịch vụ nước uống miễn phí.</p>
                        </div>
                    </div>
                </div>

                <!-- Tiện nghi 2 -->
                <div class="col-md-4" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
                    <div class="facility-card">
                        <img src="assets/images/gym.jpg" alt="Phòng gym hiện đại" aria-label="Hình ảnh phòng gym hiện đại">
                        <div class="facility-info">
                            <h5><i class="bi bi-heart-pulse"></i>Phòng Gym Hiện Đại</h5>
                            <p>Trang bị máy móc tiên tiến, không gian thoáng đãng để duy trì sức khỏe mỗi ngày.</p>
                        </div>
                    </div>
                </div>

                <!-- Tiện nghi 3 -->
                <div class="col-md-4" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="300">
                    <div class="facility-card">
                        <img src="assets/images/spa.jpg" alt="Dịch vụ spa" aria-label="Hình ảnh dịch vụ spa">
                        <div class="facility-info">
                            <h5><i class="bi bi-flower1"></i>Spa & Massage</h5>
                            <p>Thư giãn với liệu pháp tự nhiên và đội ngũ chuyên viên chuyên nghiệp.</p>
                        </div>
                    </div>
                </div>

                <!-- Tiện nghi 4 -->
                <div class="col-md-4" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="100">
                    <div class="facility-card">
                        <img src="assets/images/nhahang.jpg" alt="Nhà hàng ẩm thực" aria-label="Hình ảnh nhà hàng ẩm thực">
                        <div class="facility-info">
                            <h5><i class="bi bi-cup-straw"></i>Nhà Hàng Ẩm Thực</h5>
                            <p>Thưởng thức các món Á - Âu được chế biến bởi đầu bếp hàng đầu.</p>
                        </div>
                    </div>
                </div>

                <!-- Tiện nghi 5 -->
                <div class="col-md-4" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
                    <div class="facility-card">
                        <img src="assets/images/doxe.jpg" alt="Bãi đỗ xe miễn phí" aria-label="Hình ảnh bãi đỗ xe miễn phí">
                        <div class="facility-info">
                            <h5><i class="bi bi-car-front"></i>Bãi Đỗ Xe Miễn Phí</h5>
                            <p>Không gian rộng rãi, an ninh 24/7 cho xe ô tô và xe máy.</p>
                        </div>
                    </div>
                </div>

                <!-- Tiện nghi 6 -->
                <div class="col-md-4" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="300">
                    <div class="facility-card">
                        <img src="assets/images/wifi.jpg" alt="Wifi tốc độ cao" aria-label="Hình ảnh wifi tốc độ cao">
                        <div class="facility-info">
                            <h5><i class="bi bi-wifi"></i>Wifi Tốc Độ Cao</h5>
                            <p>Kết nối internet không giới hạn trong toàn bộ khuôn viên khách sạn.</p>
                        </div>
                    </div>
                </div>
            </div>
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