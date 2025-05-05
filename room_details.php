<?php
require_once 'config.php';
session_start();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Phòng không tồn tại.";
    exit;
}

$id = intval($_GET['id']);
$stmt = $pdo->prepare("SELECT * FROM rooms WHERE id = ?");
$stmt->execute([$id]);
$room = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$room) {
    echo "Phòng không tồn tại.";
    exit;
}

// Hàm lấy ảnh theo loại phòng nếu không có ảnh cụ thể
function getRoomImage($type) {
    switch (mb_strtolower($type, 'UTF-8')) {
        case 'đơn': return 'assets/images/phong_don.jpg';
        case 'đôi': return 'assets/images/phong_doi.jpg';
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Chi tiết phòng tại HotelLinker - Khám phá phòng nghỉ sang trọng với tiện nghi hiện đại.">
    <title>Chi Tiết Phòng - <?php echo htmlspecialchars($room['name']); ?> | HotelLinker</title>
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

        /* Room Details Section */
        .room-details-section {
            padding: 80px 0;
            background: #f9fafb;
        }
        .room-details-section h2 {
            font-size: 3rem;
            font-weight: 700;
            font-family: 'Playfair Display', serif;
            color: #111827;
            margin-bottom: 20px;
        }
        .room-image {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 12px;
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .room-image:hover {
            transform: scale(1.05);
        }
        .room-info {
            background: #fff;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .room-info:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            border-color: #d4af37;
        }
        .room-info p {
            font-size: 1.1rem;
            color: #4b5563;
            line-height: 1.6;
            margin-bottom: 15px;
        }
        .room-info strong {
            color: #111827;
            font-weight: 600;
        }
        .badge {
            font-size: 0.95rem;
            background: #f3f4f6;
            color: #4b5563;
            padding: 8px 12px;
            border-radius: 8px;
            margin-right: 8px;
            margin-bottom: 8px;
        }
        .btn-primary {
            background: #d4af37;
            border: none;
            padding: 12px 30px;
            font-family: 'Roboto', sans-serif;
            font-weight: 600;
            border-radius: 8px;
            transition: background 0.3s ease, transform 0.3s ease;
        }
        .btn-primary:hover {
            background: #b8972f;
            transform: translateY(-3px);
        }

        /* Container */
        .container {
            max-width: 1200px;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .room-details-section {
                padding: 40px 0;
            }
            .room-details-section h2 {
                font-size: 2rem;
            }
            .room-image {
                height: 300px;
            }
            .room-info {
                padding: 20px;
            }
            .navbar-brand {
                font-size: 1.5rem;
            }
            .btn-outline-primary, .btn-primary {
                padding: 6px 15px;
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
                    <li class="nav-item"><a class="nav-link active" href="#">Phòng</a></li>
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

    <!-- Room Details Section -->
    <section class="room-details-section">
        <div class="container">
            <a href="index.php" class="btn btn-outline-primary mb-4" data-aos="fade-up" data-aos-duration="1000">← Quay lại danh sách</a>
            <div class="row">
                <div class="col-md-6" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="100">
                    <?php if (!empty($room['image'])): ?>
                        <img src="assets/images/rooms/<?php echo htmlspecialchars($room['image']); ?>" class="room-image" alt="<?php echo htmlspecialchars($room['name']); ?>" loading="lazy">
                    <?php else: ?>
                        <img src="<?php echo getRoomImage($room['type']); ?>" class="room-image" alt="<?php echo htmlspecialchars($room['name']); ?>" loading="lazy">
                    <?php endif; ?>
                </div>
                <div class="col-md-6" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
                    <div class="room-info">
                        <h2><?php echo htmlspecialchars($room['name']); ?></h2>
                        <p><strong>Loại:</strong> <?php echo htmlspecialchars($room['type']); ?></p>
                        <p><strong>Sức chứa tối đa:</strong> <?php echo htmlspecialchars($room['max_guests']); ?> người</p>
                        <p><strong>Giá:</strong> <?php echo number_format($room['price'], 0, ',', '.'); ?> VNĐ/đêm</p>

                        <?php if (!empty($room['amenities'])): ?>
                            <p><strong>Tiện nghi:</strong></p>
                            <ul class="list-inline">
                                <?php
                                $icons = [
                                    'wifi' => '<i class="bi bi-wifi me-1"></i> Wifi',
                                    'tv' => '<i class="bi bi-tv me-1"></i> TV',
                                    'air_con' => '<i class="bi bi-snow me-1"></i> Điều hòa',
                                    'minibar' => '<i class="bi bi-cup-straw me-1"></i> Minibar',
                                    'spa' => '<i class="bi bi-flower1 me-1"></i> Spa',
                                    'bathtub' => '<i class="bi bi-water me-1"></i> Bồn tắm',
                                    'sea_view' => '<i class="bi bi-water me-1"></i> View biển'
                                ];
                                $amenities = explode(',', $room['amenities']);
                                foreach ($amenities as $a) {
                                    $a = trim($a);
                                    $label = $icons[$a] ?? ucfirst($a);
                                    echo "<li class='list-inline-item badge'>$label</li>";
                                }
                                ?>
                            </ul>
                        <?php endif; ?>

                        <?php if (!empty($room['description'])): ?>
                            <p><strong>Mô tả:</strong><br><?php echo nl2br(htmlspecialchars($room['description'])); ?></p>
                        <?php endif; ?>

                        <a href="booking.php?room_id=<?php echo $room['id']; ?>" class="btn btn-primary mt-3">Đặt phòng ngay</a>
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