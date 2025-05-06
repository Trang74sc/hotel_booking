<?php
require_once 'config.php';
session_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giới Thiệu - HotelLinker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        /* General Styles */
        body {
            overflow-x: hidden;
            font-family: 'Roboto', sans-serif;
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
        .btn-outline-primary {
            border-color: #d4af37;
            color: #d4af37;
            font-weight: 600;
            border-radius: 8px;
            padding: 8px 20px;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }
        .btn-outline-primary:hover {
            background: #d4af37;
            color: #fff;
            transform: translateY(-3px);
        }
        .btn-outline-danger {
            border-color: #dc3545;
            color: #dc3545;
            font-weight: 600;
            border-radius: 8px;
            padding: 8px 20px;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }
        .btn-outline-danger:hover {
            background: #dc3545;
            color: #fff;
            transform: translateY(-3px);
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

        /* Introduction Section */
        .intro-section {
            padding: 80px 0;
            background: #fff;
        }
        .intro-image {
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.5s ease;
            max-height: 500px;
            object-fit: cover;
        }
        .intro-image:hover {
            transform: translateY(-5px);
        }
        .intro-text h2 {
            font-size: 3rem;
            font-weight: 700;
            font-family: 'Playfair Display', serif;
            color: #111827;
            margin-bottom: 20px;
        }
        .intro-text p {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #4b5563;
            font-family: 'Roboto', sans-serif;
        }
        .intro-text .highlight {
            color: #000000;
            font-weight: 600;
        }

        /* Timeline Section */
        .timeline-section {
            padding: 80px 0;
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('assets/images/header.jpg') no-repeat center/cover !important;
            background-attachment: fixed;
            color: #fff;
            background-color: #f9fafb; /* Fallback color */
        }
        .timeline-section h2 {
            font-size: 3rem;
            font-weight: 700;
            font-family: 'Playfair Display', serif;
            color: #fff;
            text-align: center;
            margin-bottom: 50px;
        }
        .timeline {
            position: relative;
            max-width: 900px;
            margin: 0 auto;
        }
        .timeline::before {
            content: '';
            position: absolute;
            width: 8px;
            background: #d4af37;
            top: 0;
            bottom: 0;
            left: 50%;
            margin-left: -4px;
            border-radius: 4px;
        }
        .timeline-item {
            padding: 20px 50px;
            position: relative;
            width: 50%;
            margin-bottom: 40px;
        }
        .timeline-item:nth-child(odd) {
            left: 0;
            text-align: right;
        }
        .timeline-item:nth-child(even) {
            left: 50%;
            text-align: left;
        }
        .timeline-item::before {
            content: '';
            position: absolute;
            top: 20px;
            width: 30px;
            height: 30px;
            background: #fff;
            border: 5px solid #d4af37;
            border-radius: 50%;
            z-index: 1;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .timeline-item:hover::before {
            transform: scale(1.2);
            box-shadow: 0 0 15px rgba(212, 175, 55, 0.5);
        }
        .timeline-item:nth-child(odd)::before {
            right: -15px;
        }
        .timeline-item:nth-child(even)::before {
            left: -15px;
        }
        .timeline-content {
            padding: 25px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 12px;
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            position: relative;
        }
        .timeline-content::before {
            content: '';
            position: absolute;
            top: 20px;
            border: 10px solid transparent;
        }
        .timeline-item:nth-child(odd) .timeline-content::before {
            border-right-color: rgba(255, 255, 255, 0.9);
            right: -20px;
        }
        .timeline-item:nth-child(even) .timeline-content::before {
            border-left-color: rgba(255, 255, 255, 0.9);
            left: -20px;
        }
        .timeline-content:hover {
            transform: translateY(-5px);
        }
        .timeline-content h5 {
            font-size: 1.4rem;
            font-weight: 600;
            color: #111827;
            font-family: 'Roboto', sans-serif;
            margin-bottom: 10px;
        }
        .timeline-content p {
            font-size: 1rem;
            color: #4b5563;
            font-family: 'Roboto', sans-serif;
        }

        /* Feature Section */
        .feature-section {
            padding: 80px 0;
            background: #fff;
        }
        .feature-section h2 {
            font-size: 3rem;
            font-weight: 700;
            font-family: 'Playfair Display', serif;
            color: #111827;
            text-align: center;
            margin-bottom: 50px;
        }
        .feature-card {
            background: #fff;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 30px;
            border: 1px solid #e5e7eb;
        }
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            border-color: #000000;
        }
        .feature-card i {
            font-size: 3rem;
            color: #000000;
            margin-bottom: 20px;
        }
        .feature-card h5 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #111827;
            font-family: 'Roboto', sans-serif;
            margin-bottom: 15px;
        }
        .feature-card p {
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
            .navbar-brand {
                font-size: 1.5rem;
            }
            .btn-outline-primary, .btn-outline-danger {
                padding: 6px 15px;
                font-size: 0.85rem;
            }
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
            .intro-section, .timeline-section, .feature-section {
                padding: 40px 0;
            }
            .intro-text h2, .timeline-section h2, .feature-section h2 {
                font-size: 2rem;
            }
            .intro-image {
                margin-bottom: 20px;
            }
            .timeline-section {
                background-attachment: scroll;
            }
            .timeline::before {
                left: 20px;
            }
            .timeline-item {
                width: 100%;
                padding-left: 60px;
                padding-right: 20px;
                text-align: left;
                margin-bottom: 20px;
            }
            .timeline-item:nth-child(odd), .timeline-item:nth-child(even) {
                left: 0;
            }
            .timeline-item::before {
                left: 5px;
            }
            .timeline-content::before {
                border-left-color: rgba(255, 255, 255, 0.9);
                left: -20px;
                border-right-color: transparent;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="index.php">HotelLinker</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>" href="index.php">Trang Chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'search_rooms.php' ? 'active' : ''; ?>" href="#">Phòng</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'tiennghi.php' ? 'active' : ''; ?>" href="tiennghi.php">Tiện Nghi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'lienhe.php' ? 'active' : ''; ?>" href="lienhe.php">Liên Hệ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'gioithieu.php' ? 'active' : ''; ?>" href="gioithieu.php">Giới Thiệu</a>
                    </li>
                </ul>
                <div class="d-flex">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <span class="me-3">Xin chào, <?php echo htmlspecialchars($_SESSION['user_name'], ENT_QUOTES, 'UTF-8'); ?></span>
                        <a href="logout.php" class="btn btn-outline-danger">Đăng Xuất</a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-outline-primary me-2">Đăng Nhập</a>
                        <a href="register.php" class="btn btn-outline-primary">Đăng Ký</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="page-header">
        <div class="hero-content" data-aos="fade-up" data-aos-duration="1000">
            <h1>VỀ HotelLinker</h1>
            <p>Nơi sang trọng hiện đại hòa quyện với nét đẹp văn hóa Việt Nam, mang đến trải nghiệm lưu trú không thể quên.</p>
            <a href="booking.php" class="btn btn-primary">Đặt Phòng Ngay</a>
        </div>
    </section>

    <!-- Introduction Section -->
    <section class="intro-section">
        <div class="container">
            <div class="row align-items-center" data-aos="fade-up" data-aos-duration="1000">
                <div class="col-md-5 order-md-2">
                    <img src="assets/images/khachsan.webp" alt="Khách sạn HotelLinker" class="img-fluid intro-image" aria-label="Hình ảnh khách sạn HotelLinker">
                </div>
                <div class="col-md-7 mt-4 mt-md-0 order-md-1">
                    <div class="intro-text">
                        <h2>Khám Phá <span class="highlight">HotelLinker</span></h2>
                        <p>Nằm tại trung tâm Hà Nội, <span class="highlight">HotelLinker</span> là biểu tượng của sự <strong>sang trọng</strong> và <strong>tinh tế</strong>. Chúng tôi mang đến một không gian độc đáo, nơi thiết kế hiện đại hòa quyện với văn hóa Việt Nam, tạo nên một trải nghiệm lưu trú đẳng cấp.</p>
                        <p>Từ những phòng nghỉ sang trọng đến dịch vụ tận tâm, mỗi khoảnh khắc tại HotelLinker đều được chăm chút để mang lại sự thoải mái và hài lòng tuyệt đối. Hãy để chúng tôi biến chuyến đi của bạn thành một hành trình đáng nhớ!</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Timeline Section -->
    <section class="timeline-section">
        <h2 data-aos="fade-up" data-aos-duration="1000">Hành Trình Của HotelLinker</h2>
        <div class="container">
            <div class="timeline">
                <div class="timeline-item" data-aos="fade-right" data-aos-duration="1000">
                    <div class="timeline-content">
                        <h5>2015 - Thành Lập</h5>
                        <p>HotelLinker ra đời với đam mê trở thành khách sạn hàng đầu Hà Nội, mang đến trải nghiệm độc đáo.</p>
                    </div>
                </div>
                <div class="timeline-item" data-aos="fade-left" data-aos-duration="1000">
                    <div class="timeline-content">
                        <h5>2018 - Mở Rộng Dịch Vụ</h5>
                        <p>Ra mắt nhà hàng ẩm thực cao cấp và spa thư giãn, nhận được nhiều đánh giá tích cực từ khách hàng.</p>
                    </div>
                </div>
                <div class="timeline-item" data-aos="fade-right" data-aos-duration="1000">
                    <div class="timeline-content">
                        <h5>2020 - Giải Thưởng Du Lịch</h5>
                        <p>Được vinh danh là "Khách Sạn Tốt Nhất Hà Nội" bởi Hiệp hội Du lịch Việt Nam.</p>
                    </div>
                </div>
                <div class="timeline-item" data-aos="fade-left" data-aos-duration="1000">
                    <div class="timeline-content">
                        <h5>2025 - Tương Lai</h5>
                        <p>Cam kết đổi mới và mở rộng để mang đến những trải nghiệm lưu trú đẳng cấp hơn nữa.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Feature Section -->
    <section class="feature-section">
        <h2 data-aos="fade-up" data-aos-duration="1000">Điều Gì Làm Nên HotelLinker?</h2>
        <div class="container">
            <div class="row">
                <div class="col-md-4" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="100">
                    <div class="feature-card">
                        <i class="bi bi-house-door-fill"></i>
                        <h5>Thiết Kế Độc Đáo</h5>
                        <p>Mỗi phòng nghỉ là một tác phẩm nghệ thuật, kết hợp phong cách hiện đại và nét văn hóa Việt Nam.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
                    <div class="feature-card">
                        <i class="bi bi-egg-fried"></i>
                        <h5>Ẩm Thực Tinh Tế</h5>
                        <p>Thưởng thức các món ăn độc đáo từ đầu bếp hàng đầu, mang đậm hương vị Việt và quốc tế.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="300">
                    <div class="feature-card">
                        <i class="bi bi-heart-fill"></i>
                        <h5>Dịch Vụ Tận Tâm</h5>
                        <p>Đội ngũ nhân viên luôn sẵn sàng đáp ứng mọi nhu cầu, mang lại trải nghiệm cá nhân hóa.</p>
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