<?php
require_once 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    header("Location: login.php");
    exit;
}

// Validate input parameters
$check_in = $_GET['check_in'] ?? '';
$check_out = $_GET['check_out'] ?? '';
$adults = intval($_GET['adults'] ?? 1);
$children = intval($_GET['children'] ?? 0);

// Validate dates
$today = date('Y-m-d');
if (empty($check_in) || empty($check_out) || $check_in < $today || $check_out <= $check_in) {
    header('Location: index.php?error=invalid_dates');
    exit;
}

// Build the query to find available rooms
$query = "
    SELECT r.* 
    FROM rooms r 
    WHERE r.id NOT IN (
        SELECT b.room_id 
        FROM bookings b 
        WHERE (b.check_in <= ? AND b.check_out >= ?)
        OR (b.check_in <= ? AND b.check_out >= ?)
        OR (b.check_in >= ? AND b.check_out <= ?)
    )
    AND r.max_guests >= ?
";

$params = [
    $check_in, $check_in,
    $check_out, $check_out,
    $check_in, $check_out,
    ($adults + $children)
];

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$available_rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Filter rooms by amenities if selected
if (!empty($_GET['amenities'])) {
    $filtered_rooms = [];
    foreach ($available_rooms as $room) {
        $include_room = true;
        foreach ($_GET['amenities'] as $amenity) {
            if (!isset($room['amenities']) || strpos($room['amenities'], $amenity) === false) {
                $include_room = false;
                break;
            }
        }
        if ($include_room) {
            $filtered_rooms[] = $room;
        }
    }
    $available_rooms = $filtered_rooms;
}

// Calculate number of nights
$check_in_date = new DateTime($check_in);
$check_out_date = new DateTime($check_out);
$interval = $check_in_date->diff($check_out_date);
$total_nights = $interval->days;
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Kết quả tìm kiếm phòng tại HotelLinker - Đặt phòng khách sạn sang trọng tại Hà Nội với giá tốt nhất.">
    <title>Kết Quả Tìm Kiếm - HotelLinker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.9/dist/flatpickr.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        body {
            background-color: #f9fafb;
            font-family: 'Roboto', sans-serif;
            color: #111827;
            overflow-x: hidden;
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
        }
        .btn-outline-primary:hover {
            background: #d4af37;
            color: #fff;
            transform: translateY(-3px);
        }

        /* Search Summary */
        .search-summary {
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            margin-bottom: 40px;
            transition: transform 0.3s ease;
        }
        .search-summary:hover {
            transform: translateY(-5px);
        }
        .search-summary h4 {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            color: #111827;
            margin-bottom: 20px;
        }
        .search-summary .date {
            color: #d4af37;
            font-weight: 600;
        }
        .search-summary .form-control {
            border-radius: 8px;
            font-size: 0.95rem;
        }

        /* Filter Sidebar */
        .filter-sidebar {
            background: #ffffff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 20px;
        }
        .filter-sidebar h5 {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            color: #111827;
            margin-bottom: 20px;
        }
        .form-check-label {
            font-size: 0.95rem;
            color: #4b5563;
            transition: color 0.3s ease;
        }
        .form-check-input:checked + .form-check-label {
            color: #d4af37;
        }
        .btn-primary {
            background: #d4af37;
            border: none;
            padding: 12px 20px;
            font-weight: 600;
            border-radius: 8px;
            transition: background 0.3s ease, transform 0.3s ease;
        }
        .btn-primary:hover {
            background: #b8972f;
            transform: translateY(-3px);
        }

        /* Room Cards */
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
        .room-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        .room-card:hover .room-image {
            transform: scale(1.05);
        }
        .room-info {
            padding: 20px;
        }
        .room-info h4 {
            font-family: 'Playfair Display', serif;
            font-size: 1.6rem;
            font-weight: 600;
            color: #111827;
            margin-bottom: 15px;
        }
        .facility-item {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 10px;
            color: #4b5563;
            font-size: 0.95rem;
        }
        .price-value {
            font-size: 1.3rem;
            font-weight: 600;
            color: #d4af37;
        }
        .price-unit {
            font-size: 0.85rem;
            color: #4b5563;
        }

        /* Alert */
        .alert-info {
            border-radius: 8px;
            background: #e0f2fe;
            border-color: #bae6fd;
            color: #0369a1;
            font-size: 1rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .search-summary {
                padding: 20px;
            }
            .search-summary h4 {
                font-size: 1.5rem;
            }
            .filter-sidebar {
                padding: 15px;
            }
            .room-image {
                height: 200px;
            }
            .room-info h4 {
                font-size: 1.4rem;
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

    <div class="container py-5">
        <!-- Search Summary -->
        <div class="search-summary" data-aos="fade-up" data-aos-duration="1000">
            <h4>Kết Quả Tìm Kiếm</h4>
            <div class="row align-items-center">
                <div class="col-md-8">
                    <p class="mb-2">
                        <i class="bi bi-calendar-check me-2"></i>
                        Nhận phòng: <span class="date"><?php echo date('d/m/Y', strtotime($check_in)); ?></span> -
                        Trả phòng: <span class="date"><?php echo date('d/m/Y', strtotime($check_out)); ?></span>
                        (<?php echo $total_nights; ?> đêm)
                    </p>
                    <p class="mb-0">
                        <i class="bi bi-people me-2"></i>
                        Số người: <?php echo $adults; ?> người lớn<?php echo $children ? " và $children trẻ em" : ''; ?>
                    </p>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="index.php" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left me-2"></i>Tìm Kiếm Lại
                    </a>
                </div>
            </div>
        </div>

        <!-- Search Results -->
        <div class="row">
            <!-- Sidebar Filters -->
            <div class="col-md-3" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="100">
                <div class="filter-sidebar">
                    <h5>Bộ Lọc</h5>
                    <form method="GET" action="">
                        <input type="hidden" name="check_in" value="<?php echo htmlspecialchars($check_in); ?>">
                        <input type="hidden" name="check_out" value="<?php echo htmlspecialchars($check_out); ?>">
                        <input type="hidden" name="adults" value="<?php echo $adults; ?>">
                        <input type="hidden" name="children" value="<?php echo $children; ?>">

                        <div class="mb-4">
                            <h6>Tiện Nghi</h6>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="amenities[]" value="wifi" id="wifi"
                                    <?php echo (isset($_GET['amenities']) && in_array('wifi', $_GET['amenities'])) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="wifi"><i class="bi bi-wifi me-2"></i>Wifi</label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="amenities[]" value="air_con" id="air_con"
                                    <?php echo (isset($_GET['amenities']) && in_array('air_con', $_GET['amenities'])) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="air_con"><i class="bi bi-snow me-2"></i>Điều Hòa</label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="amenities[]" value="tv" id="tv"
                                    <?php echo (isset($_GET['amenities']) && in_array('tv', $_GET['amenities'])) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="tv"><i class="bi bi-tv me-2"></i>Tivi</label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="amenities[]" value="spa" id="spa"
                                    <?php echo (isset($_GET['amenities']) && in_array('spa', $_GET['amenities'])) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="spa"><i class="bi bi-droplet me-2"></i>Spa</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Áp Dụng Bộ Lọc</button>
                    </form>
                </div>
            </div>

            <!-- Room Results -->
            <div class="col-md-9">
                <?php if (!empty($available_rooms)): ?>
                    <div class="row">
                        <?php foreach ($available_rooms as $index => $room): ?>
                            <div class="col-md-6 mb-4" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="<?php echo ($index % 3) * 100; ?>">
                                <div class="room-card">
                                    <img src="assets/images/rooms/<?php echo $room['image'] ?? 'default.jpg'; ?>" 
                                         alt="Phòng <?php echo htmlspecialchars($room['name']); ?>" 
                                         class="room-image" 
                                         loading="lazy">
                                    <div class="room-info">
                                        <h4><?php echo htmlspecialchars($room['name']); ?></h4>
                                        <div class="mb-3">
                                            <h6 class="text-muted mb-2">Thông Tin Phòng</h6>
                                            <div class="facility-item">
                                                <i class="bi bi-people"></i>
                                                <span>Tối đa <?php echo htmlspecialchars($room['max_guests']); ?> người</span>
                                            </div>
                                            <div class="facility-item">
                                                <i class="bi bi-house-door"></i>
                                                <span>Loại: <?php echo htmlspecialchars($room['type']); ?></span>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <div>
                                                <div class="price-value">
                                                    <?php echo number_format($room['price'], 0, ',', '.'); ?> VNĐ
                                                </div>
                                                <div class="price-unit">mỗi đêm</div>
                                            </div>
                                            <form method="POST" action="booking.php">
                                                <input type="hidden" name="room_id" value="<?php echo $room['id']; ?>">
                                                <input type="hidden" name="check_in" value="<?php echo htmlspecialchars($check_in); ?>">
                                                <input type="hidden" name="check_out" value="<?php echo htmlspecialchars($check_out); ?>">
                                                <input type="hidden" name="adults" value="<?php echo $adults; ?>">
                                                <input type="hidden" name="children" value="<?php echo $children; ?>">
                                                <button type="submit" class="btn btn-primary">Đặt Ngay</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info" data-aos="fade-up" data-aos-duration="1000">
                        <i class="bi bi-info-circle me-2"></i>
                        Không tìm thấy phòng trống cho thời gian bạn chọn.
                        <a href="index.php" class="alert-link ms-2">Thử tìm kiếm khác</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

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