<?php
require_once 'config.php'; 
session_start(); 
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liên Hệ - HotelLinker</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">

    <!-- CSS nội bộ -->
    <style>
        body {
            overflow-x: hidden; /* Ngăn cuộn ngang */
            font-family: 'Roboto', sans-serif; /* Font chính */
        }

        /* Navbar */
        .navbar {
            background: #fff; /* Màu nền trắng */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Hiệu ứng đổ bóng */
            padding: 15px 0;
        }
        .navbar-brand {
            font-family: 'Playfair Display', serif; /* Font logo */
            font-size: 2rem;
            color: #111827; /* Màu chữ logo */
        }
        .nav-link {
            font-weight: 500;
            color: #4b5563; /* Màu chữ liên kết */
            transition: color 0.3s ease; /* Hiệu ứng chuyển màu mượt */
        }
        .nav-link:hover, .nav-link.active {
            color: #d4af37; /* Màu khi hover hoặc active */
        }
        .btn-outline-primary {
            border-color: #d4af37; /* Viền nút */
            color: #d4af37; /* Màu chữ */
            font-weight: 600;
            border-radius: 8px;
            padding: 8px 20px;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }
        .btn-outline-primary:hover {
            background: #d4af37; /* Màu nền khi hover */
            color: #fff;
            transform: translateY(-3px); /* Hiệu ứng nâng lên */
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
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.5)), url('assets/images/header.jpg') no-repeat center/cover; /* Hình nền với lớp phủ tối */
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
            z-index: 1; /* Đảm bảo nội dung nằm trên lớp phủ */
        }
        .page-header h1 {
            font-size: 4.5rem;
            font-weight: 700;
            font-family: 'Playfair Display', serif;
            letter-spacing: 2px;
            text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.5); /* Hiệu ứng bóng chữ */
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
            background: #b8972f; /* Màu nền khi hover */
            transform: translateY(-3px);
        }

        /* Contact Section */
        .contact-section {
            padding: 80px 0;
            background: #fff;
        }
        .contact-section h2 {
            font-size: 3rem;
            font-weight: 700;
            font-family: 'Playfair Display', serif;
            color: #111827;
            text-align: center;
            margin-bottom: 30px;
        }
        .contact-section p.lead {
            font-size: 1.2rem;
            color: #4b5563;
            text-align: center;
            max-width: 800px;
            margin: 0 auto 50px;
            font-family: 'Roboto', sans-serif;
        }
        .contact-card, .form-card {
            background: #fff;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.1); /* Hiệu ứng đổ bóng */
            margin-bottom: 30px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid #e5e7eb;
            min-height: 520px;
            display: flex;
            flex-direction: column;
        }
        .contact-card:hover, .form-card:hover {
            transform: translateY(-10px); /* Hiệu ứng nâng lên khi hover */
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            border-color: #d4af37;
        }
        .contact-info, .form-info {
            padding: 30px;
            background: #fff;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        .contact-info h4, .form-info h4 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #111827;
            font-family: 'Roboto', sans-serif;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }
        .contact-info h4 i, .form-info h4 i {
            font-size: 2rem;
            color: #000000;
            margin-right: 15px;
            transition: transform 0.3s ease;
        }
        .contact-card:hover h4 i, .form-card:hover h4 i {
            transform: rotate(10deg); /* Hiệu ứng xoay icon khi hover */
        }
        .contact-info p, .form-info p {
            font-size: 1rem;
            color: #4b5563;
            line-height: 1.6;
            font-family: 'Roboto', sans-serif;
        }
        .contact-info h5 {
            font-size: 1.2rem;
            font-weight: 600;
            color: #111827;
            margin-top: 20px;
            display: flex;
            align-items: center;
            font-family: 'Roboto', sans-serif;
        }
        .contact-info h5 i {
            font-size: 1.5rem;
            color: #000000;
            margin-right: 10px;
        }
        .social-links {
            margin-top: auto;
            padding-top: 20px;
        }

        /* Form Styling */
        .form-info .form-label {
            font-weight: 500;
            color: #111827;
            font-family: 'Roboto', sans-serif;
        }
        .form-info .form-control {
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            padding: 12px;
            font-size: 0.95rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            font-family: 'Roboto', sans-serif;
        }
        .form-info .form-control:focus {
            border-color: #d4af37;
            box-shadow: 0 0 8px rgba(212, 175, 55, 0.2); /* Hiệu ứng bóng khi focus */
        }
        .form-info textarea.form-control {
            resize: vertical;
            min-height: 120px;
        }
        .form-info .btn-primary {
            background: #d4af37;
            border: none;
            padding: 12px 30px;
            font-weight: 600;
            border-radius: 8px;
            transition: background 0.3s ease, transform 0.3s ease;
            font-family: 'Roboto', sans-serif;
        }
        .form-info .btn-primary:hover {
            background: #b8972f;
            transform: translateY(-2px);
        }

        /* Social Media Links */
        .social-links {
            margin-top: 20px;
        }
        .social-links a {
            font-size: 1.8rem;
            color: #111827;
            margin: 0 15px;
            transition: color 0.3s ease, transform 0.3s ease;
        }
        .social-links a:hover {
            color: #d4af37;
            transform: scale(1.2); /* Hiệu ứng phóng to khi hover */
        }

        /* Map Section */
        .map-section {
            margin-top: 50px;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.1);
        }
        .map-section iframe {
            width: 100%;
            height: 400px;
            border: none;
        }

        /* Container */
        .container {
            max-width: 1200px; /* Độ rộng tối đa */
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
            .contact-section {
                padding: 40px 0;
            }
            .contact-section h2 {
                font-size: 2rem;
            }
            .contact-info, .form-info {
                padding: 20px;
            }
            .contact-card, .form-card {
                min-height: auto;
            }
            .map-section iframe {
                height: 300px;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand" href="index.php">HotelLinker</a>
            <!-- Nút toggle cho mobile -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Menu điều hướng -->
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Trang Chủ</a></li>
                    <li class="nav-item"><a class="nav-link" href="search_rooms.php">Phòng</a></li>
                    <li class="nav-item"><a class="nav-link" href="tiennghi.php">Tiện Nghi</a></li>
                    <li class="nav-item"><a class="nav-link active" href="lienhe.php">Liên Hệ</a></li>
                    <li class="nav-item"><a class="nav-link" href="gioithieu.php">Giới Thiệu</a></li>
                </ul>
                <!-- Nút đăng nhập/đăng xuất -->
                <div class="d-flex">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <!-- Hiển thị tên người dùng và nút đăng xuất nếu đã đăng nhập -->
                        <span class="me-3">Xin chào, <?php echo htmlspecialchars($_SESSION['user_name'], ENT_QUOTES, 'UTF-8'); ?></span>
                        <a href="logout.php" class="btn btn-outline-danger">Đăng Xuất</a>
                    <?php else: ?>
                        <!-- Hiển thị nút đăng nhập và đăng ký nếu chưa đăng nhập -->
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
            <h1>LIÊN HỆ VỚI CHÚNG TÔI</h1>
            <p>Chúng tôi luôn sẵn sàng hỗ trợ bạn. Gửi câu hỏi hoặc yêu cầu đặt phòng, đội ngũ HotelLinker sẽ phản hồi nhanh chóng!</p>
            <a href="booking.php" class="btn btn-primary">Đặt Phòng Ngay</a>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section">
        <div class="container">
            <h2 data-aos="fade-up" data-aos-duration="1000">Liên Hệ HotelLinker</h2>
            <p class="lead" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="100">
                Kết nối với chúng tôi qua email, điện thoại hoặc biểu mẫu dưới đây để nhận hỗ trợ nhanh chóng và chuyên nghiệp.
            </p>
            <div class="row g-4">
                <!-- Thông tin liên hệ -->
                <div class="col-md-6" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="100">
                    <div class="contact-card">
                        <div class="contact-info">
                            <h4><i class="bi bi-info-circle"></i>Thông Tin Liên Hệ</h4>
                            <p>Liên hệ với chúng tôi để được tư vấn về dịch vụ, đặt phòng hoặc giải đáp mọi thắc mắc.</p>
                            <h5><i class="bi bi-geo-alt-fill"></i>Địa Chỉ</h5>
                            <p>12 Chùa Bộc, Hà Nội</p>
                            <h5><i class="bi bi-telephone-fill"></i>Điện Thoại</h5>
                            <p>(+84) 123 456 789</p>
                            <h5><i class="bi bi-envelope-fill"></i>Email</h5>
                            <p>contact@hotellinker.com</p>
                            <h5><i class="bi bi-clock-fill"></i>Giờ Làm Việc</h5>
                            <p>Thứ 2 - Chủ Nhật: 7:00 - 23:00</p>
                            <!-- Liên kết mạng xã hội -->
                            <div class="social-links">
                                <a href="#" title="Facebook"><i class="bi bi-facebook"></i></a>
                                <a href="#" title="Instagram"><i class="bi bi-instagram"></i></a>
                                <a href="#" title="Twitter"><i class="bi bi-twitter"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Biểu mẫu liên hệ -->
                <div class="col-md-6" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
                    <div class="form-card">
                        <div class="form-info">
                            <h4><i class="bi bi-envelope-open"></i>Gửi Tin Nhắn</h4>
                            <p>Điền thông tin dưới đây, chúng tôi sẽ liên hệ lại trong thời gian sớm nhất.</p>
                            <form method="post" action="process_contact.php">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Họ và Tên</label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Nhập họ và tên" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="example@email.com" required>
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Số Điện Thoại</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="Nhập số điện thoại" required>
                                </div>
                                <div class="mb-3">
                                    <label for="message" class="form-label">Tin Nhắn</label>
                                    <textarea class="form-control" id="message" name="message" rows="5" placeholder="Hãy để lại tin nhắn của bạn..." required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Gửi Tin Nhắn</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bản đồ Google Maps -->
            <div class="map-section" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="300">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3724.506164373315!2d105.82459231474992!3d21.008783986012024!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ab4b9f4c0b5b%3A0x4c3dbba8b0e5e5b7!2zMTIgQ2jDuWEgQuG7mWMsIEtpbSBMacOqbiwgxJDhu5FuZyDEkOG6oW0sIEjDoCBO4buZaSwgVmnhu4d0IE5hbQ!5e0!3m2!1svi!2s!4v1698765432109!5m2!1svi!2s" allowfullscreen="" loading="lazy"></iframe>
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