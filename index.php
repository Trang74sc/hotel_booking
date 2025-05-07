<?php
require_once 'config.php';
session_start();
$stmt = $pdo->query("SELECT * FROM rooms");
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
function getRoomImage($type) {
    switch (strtolower($type)) {
        case 'Đơn': return 'assets/images/phong_don.jpg';
        case 'Đôi': return 'assets/images/phong-doi.jpg';
        case 'suite': return 'assets/images/phong_suite.jpeg';
        case 'deluxe': return 'assets/images/phong_deluxe.jpg';
        case 'dorm': return 'assets/images/phong_dorm.jpg';
        case 'superior': return 'assets/images/phong_superior.jpg';
        default: return 'assets/images/rooms/default.jpg';
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <!-- Cấu hình meta cho mã hóa, responsive và SEO -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="HotelLinker - Đặt phòng khách sạn sang trọng tại Hà Nội với giá tốt nhất. Khám phá các phòng nghỉ đẳng cấp và dịch vụ hàng đầu.">
    <title>HotelLinker - Trang Chủ</title>
    <!-- Nhúng Bootstrap, Bootstrap Icons, Google Fonts, AOS và CSS tùy chỉnh -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        /* Định dạng chung cho trang */
        body {
            overflow-x: hidden;
            font-family: 'Roboto', sans-serif;
        }

        /* Thanh điều hướng (navbar) */
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

        /* Phần tiêu đề (hero section) với ảnh nền và form tìm kiếm */
        .hero-section {
            position: relative;
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.5)), url('assets/images/header.jpg') no-repeat center/cover;
            height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: #fff;
        }
        .hero-content h1 {
            font-size: 4rem;
            font-weight: 700;
            font-family: 'Playfair Display', serif;
            text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.5);
            margin-bottom: 20px;
        }
        .hero-content p {
            font-size: 1.2rem;
            max-width: 600px;
            margin: 0 auto 30px;
        }
        .search-box {
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            max-width: 900px;
            margin: 0 auto;
            transition: transform 0.3s ease;
        }
        .search-box:hover {
            transform: translateY(-5px);
        }
        .search-box h3 {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            color: #111827;
            margin-bottom: 20px;
        }
        .search-btn {
            background: #d4af37;
            border: none;
            padding: 12px 20px;
            font-weight: 600;
            border-radius: 8px;
            transition: background 0.3s ease, transform 0.3s ease;
        }
        .search-btn:hover {
            background: #b8972f;
            transform: translateY(-3px);
        }
        .form-control, .form-select {
            border-radius: 8px;
            font-size: 0.95rem;
        }
        .alert-warning {
            border-radius: 8px;
            font-size: 1rem;
        }

        /* Phần danh sách phòng */
        .room-section {
            padding: 80px 0;
            background: #f9fafb;
        }
        .room-section h2 {
            font-size: 3rem;
            font-weight: 700;
            font-family: 'Playfair Display', serif;
            color: #111827;
            text-align: center;
            margin-bottom: 50px;
        }
        .room-card {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 30px;
            border: 1px solid #e5e7eb;
        }
        .room-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            border-color: #d4af37;
        }
        .room-card img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        .room-card:hover img {
            transform: scale(1.05);
        }
        .room-info {
            padding: 20px;
        }
        .room-title {
            font-size: 1.6rem;
            font-weight: 600;
            font-family: 'Playfair Display', serif;
            color: #111827;
            margin-bottom: 15px;
        }
        .room-features {
            font-size: 0.95rem;
            color: #4b5563;
            margin-bottom: 15px;
        }
        .room-price {
            font-size: 1.3rem;
            font-weight: 600;
            color: #d4af37;
        }

        /* Responsive: điều chỉnh giao diện cho màn hình nhỏ */
        @media (max-width: 768px) {
            .hero-section {
                height: 60vh;
                background-attachment: scroll;
            }
            .hero-content h1 {
                font-size: 2.5rem;
            }
            .hero-content p {
                font-size: 1rem;
            }
            .search-box {
                padding: 20px;
            }
            .search-box h3 {
                font-size: 1.5rem;
            }
            .room-section {
                padding: 40px 0;
            }
            .room-section h2 {
                font-size: 2rem;
            }
            .room-card img {
                height: 200px;
            }
            .navbar-brand {
                font-size: 1.5rem;
            }
            .btn-outline-primary {
                padding: 6px 15px;
                font-size: 0.85rem;
            }
        }
    </style>
</head>
<body>
    <!-- Thanh điều hướng (Navbar) -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="index.php">HotelLinker</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link active" href="index.php">Trang Chủ</a></li>
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
                        <a href="register.php" class="btn btn-outline-primary">Đăng Ký</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Phần tiêu đề (Hero Section) -->
    <section class="hero-section">
        <div class="hero-content" data-aos="fade-up" data-aos-duration="1000">
            <h1>CHÀO MỪNG ĐẾN VỚI HotelLinker</h1>
            <p>Khám phá không gian sang trọng và tiện nghi tại trung tâm Hà Nội.</p>
            <div class="search-box" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
                <h3>Tìm Phòng Hoàn Hảo</h3>

                <!-- Form tìm phòng -->
                <?php if (isset($_SESSION['user_id'])): ?>
                    <form action="search_rooms.php" method="GET" id="searchForm">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label" style="color: black;">Ngày Nhận Phòng</label>
                                <input type="date" class="form-control" name="check_in" id="check_in" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" style="color: black;">Ngày Trả Phòng</label>
                                <input type="date" class="form-control" name="check_out" id="check_out" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label" style="color: black;">Người Lớn</label>
                                <select class="form-select" name="adults" required>
                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label" style="color: black;">Trẻ Em</label>
                                <select class="form-select" name="children">
                                    <?php for($i = 0; $i <= 3; $i++): ?>
                                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">&nbsp;</label>
                                <button type="submit" class="btn search-btn w-100">
                                    <i class="bi bi-search me-2"></i>Tìm Phòng
                                </button>
                            </div>
                        </div>
                    </form>
                <?php else: ?>
                    <div class="alert alert-warning text-center">
                        Vui lòng <a href="login.php" class="alert-link">đăng nhập</a> để sử dụng chức năng tìm phòng.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Phần danh sách phòng-->
    <section class="room-section">
        <h2 data-aos="fade-up" data-aos-duration="1000">KHÁM PHÁ CÁC PHÒNG NGHỈ</h2>
        <div class="container">
            <div class="row">
                <?php foreach ($rooms as $room): ?>
                    <div class="col-md-4" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="<?php echo (array_search($room, $rooms) % 3) * 100; ?>">
                        <div class="room-card">
                            <?php if (!empty($room['image'])): ?>
                                <img src="assets/images/rooms/<?php echo htmlspecialchars($room['image']); ?>" 
                                     alt="Phòng <?php echo htmlspecialchars($room['name']); ?> tại HotelLinker" 
                                     class="room-image" 
                                     loading="lazy">
                            <?php else: ?>
                                <img src="<?php echo getRoomImage($room['type']); ?>" 
                                     alt="Phòng <?php echo htmlspecialchars($room['name']); ?> tại HotelLinker" 
                                     class="room-image" 
                                     loading="lazy">
                            <?php endif; ?>
                            
                            <div class="room-info">
                                <h3 class="room-title"><?php echo htmlspecialchars($room['name']); ?></h3>
                                <div class="room-features">
                                    <div class="mb-2"><i class="bi bi-people me-2"></i>Tối đa: <?php echo htmlspecialchars($room['max_guests']); ?> người</div>
                                    <div><i class="bi bi-house-door me-2"></i>Loại: <?php echo htmlspecialchars($room['type']); ?></div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="room-price"><?php echo number_format($room['price'], 0, ',', '.'); ?> VNĐ/đêm</div>
                                    <a href="room_details.php?id=<?php echo $room['id']; ?>" class="btn btn-outline-primary">Chi Tiết</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
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


        const today = new Date().toISOString().split('T')[0];
        const checkInInput = document.getElementById('check_in');
        const checkOutInput = document.getElementById('check_out');
        const searchForm = document.getElementById('searchForm');


        checkInInput.min = today;
        

        checkInInput.addEventListener('change', function() {
            const checkInDate = new Date(this.value);
            const nextDay = new Date(checkInDate);
            nextDay.setDate(checkInDate.getDate() + 1);
            
            checkOutInput.min = nextDay.toISOString().split('T')[0];
            
            // Nếu ngày trả phòng không hợp lệ, tự động đặt thành ngày sau ngày nhận phòng
            if (checkOutInput.value && new Date(checkOutInput.value) <= checkInDate) {
                checkOutInput.value = nextDay.toISOString().split('T')[0];
            }
        });

        // Kiểm tra form trước khi gửi
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const checkIn = new Date(checkInInput.value);
            const checkOut = new Date(checkOutInput.value);
            
            if (!checkInInput.value || !checkOutInput.value) {
                alert('Vui lòng chọn ngày nhận phòng và trả phòng');
                return;
            }
            if (checkIn >= checkOut) {
                alert('Ngày trả phòng phải sau ngày nhận phòng');
                return;
            }
            
            // Gửi form nếu hợp lệ
            this.submit();
        });
    </script>
</body>
</html>