<?php
require_once 'config.php';
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
if (!isset($_SESSION['user_id'])) {
    // Gửi lại dữ liệu đã nhập nếu cần
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

// Create parameters array
$params = [
    $check_in, $check_in,
    $check_out, $check_out,
    $check_in, $check_out,
    ($adults + $children) // Total guests
];

// Prepare and execute the query
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$available_rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Filter rooms by amenities if selected
if (!empty($_GET['amenities'])) {
    $filtered_rooms = [];
    foreach ($available_rooms as $room) {
        $include_room = true;
        foreach ($_GET['amenities'] as $amenity) {
            // Skip filtering if amenities column doesn't exist
            if (!isset($room['amenities'])) {
                $include_room = false;
                break;
            }
            // Check if the room has all selected amenities
            if (strpos($room['amenities'], $amenity) === false) {
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
    <title>Kết Quả Tìm Kiếm -HotelLinker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
    body {
        background-color: #f1f5f9;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #1e293b;
    }

    .search-summary {
        background: #ffffff;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        margin-bottom: 30px;
    }

    .search-summary .date {
        color: #2563eb;
        font-weight: 600;
    }

    .filter-sidebar {
        background: #ffffff;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
        position: sticky;
        top: 20px;
    }

    .filter-sidebar h5 {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 20px;
    }

    .form-check-label {
        font-size: 15px;
        color: #334155;
    }

    .btn-primary {
        background-color: #2563eb;
        border-color: #2563eb;
    }

    .btn-primary:hover {
        background-color: #1d4ed8;
        border-color: #1d4ed8;
    }

    .room-card {
        background: #ffffff;
        border: none;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transition: transform 0.2s ease-in-out;
    }

    .room-card:hover {
        transform: translateY(-5px);
    }

    .room-image {
        width: 100%;
        height: 240px;
        object-fit: cover;
    }

    .room-info {
        padding: 20px;
        border-top: 1px solid #f1f5f9;
    }

    .room-info h4 {
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 10px;
        color: #0f172a;
    }

    .facility-item {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 8px;
        color: #64748b;
        font-size: 14px;
    }

    .text-muted {
        color: #64748b !important;
        font-size: 14px;
    }

    .price-value {
        font-size: 22px;
        font-weight: bold;
        color: #1d4ed8;
    }

    .price-unit {
        font-size: 13px;
        color: #64748b;
    }

    .btn-outline-primary {
        border-radius: 8px;
        padding: 8px 20px;
        font-weight: 500;
    }

    .btn-primary {
        border-radius: 8px;
        padding: 8px 20px;
        font-weight: 500;
    }

    .alert-info {
        background-color: #e0f2fe;
        border-color: #bae6fd;
        color: #0369a1;
    }

    .alert a {
        font-weight: 500;
    }

    @media (max-width: 768px) {
        .room-info {
            padding: 16px;
        }
        .room-image {
            height: 200px;
        }
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
        <a class="navbar-brand" href="#"> HotelLinker</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Trang Chủ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="#">Phòng</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="tiennghi.php">Tiện Nghi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="lienhe.php">Liên Hệ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="gioithieu.php">Giới Thiệu</a>
                </li>
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

    <div class="container py-4">
        <!-- Search Summary -->
        <div class="search-summary">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="mb-3">Kết quả tìm kiếm</h4>
                    <p class="mb-0">
                        Ngày nhận phòng: <span class="date"><?php echo date('d/m/Y', strtotime($check_in)); ?></span> -
                        Ngày trả phòng: <span class="date"><?php echo date('d/m/Y', strtotime($check_out)); ?></span>
                        (<?php echo $total_nights; ?> đêm)
                    </p>
                    <p class="mb-0">
                        Số người: <?php echo $adults; ?> người lớn<?php echo $children ? " và $children trẻ em" : ''; ?>
                    </p>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="index.php" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left me-2"></i>Tìm kiếm khác
                    </a>
                </div>
            </div>
        </div>

        <!-- Kết quả tìm kiếm -->
        <div class="row">
            <!-- Sidebar Filters -->
            <div class="col-md-3">
                <div class="filter-sidebar">
                    <h5 class="mb-4">Bộ lọc</h5>
                    <form method="GET" action="">
                        <input type="hidden" name="check_in" value="<?php echo htmlspecialchars($check_in); ?>">
                        <input type="hidden" name="check_out" value="<?php echo htmlspecialchars($check_out); ?>">
                        <input type="hidden" name="adults" value="<?php echo $adults; ?>">
                        <input type="hidden" name="children" value="<?php echo $children; ?>">

                        <div class="mb-4">
                            <h6>Tiện nghi</h6>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="amenities[]" value="wifi" id="wifi"
                                    <?php echo (isset($_GET['amenities']) && in_array('wifi', $_GET['amenities'])) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="wifi">Wifi</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="amenities[]" value="air_con" id="air_con"
                                    <?php echo (isset($_GET['amenities']) && in_array('air_con', $_GET['amenities'])) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="air_con">Điều Hòa</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="amenities[]" value="tv" id="tv"
                                    <?php echo (isset($_GET['amenities']) && in_array('tv', $_GET['amenities'])) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="tv">Tivi</label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="amenities[]" value="spa" id="spa"
                                    <?php echo (isset($_GET['amenities']) && in_array('spa', $_GET['amenities'])) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="spa">Spa</label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Áp dụng</button>
                    </form>
                </div>
            </div>

            <!-- Room Results -->
            <div class="col-md-9">
                <?php if (!empty($available_rooms)): ?>
                    <div class="row">
                        <?php foreach ($available_rooms as $room): ?>
                            <div class="col-md-6 mb-4">
                                <div class="room-card">
                                    <img src="assets/images/rooms/<?php echo $room['image'] ?? 'default.jpg'; ?>" 
                                         alt="<?php echo htmlspecialchars($room['name']); ?>" 
                                         class="room-image">
                                    
                                    <div class="room-info">
                                        <h4><?php echo htmlspecialchars($room['name']); ?></h4>
                                        
                                        <div class="mb-3">
                                            <h6 class="text-muted mb-2">Thông tin phòng</h6>
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
                                                    <?php echo number_format($room['price'], 0, ',', '.'); ?> vnđ
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
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        Không tìm thấy phòng trống cho thời gian bạn chọn.
                        <a href="index.php" class="alert-link ms-2">Thử tìm kiếm khác</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php require_once 'footer.php'; ?>
</body>
</html> 