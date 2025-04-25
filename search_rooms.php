<?php
require_once 'config.php';

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
        .search-summary {
            background: #f8fafc;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        .search-summary .date {
            color: #2563eb;
            font-weight: 500;
        }
        .filter-sidebar {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: sticky;
            top: 20px;
        }
        .room-image {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 10px;
        }
        .amenities-list {
            display: flex;
            gap: 15px;
            margin: 10px 0;
        }
        .amenities-list span {
            color: #666;
        }
        .room-info {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 20px;
        }
        .room-details {
            flex: 1;
        }
        .room-pricing {
            text-align: right;
            min-width: 200px;
        }
        .room-card {
            margin-bottom: 30px;
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .facility-item {
            display: inline-block;
            margin-right: 20px;
            color: #666;
        }
        .guest-info {
            color: #666;
            margin: 10px 0;
        }
        .price-value {
            font-size: 24px;
            font-weight: bold;
            color: #2563eb;
        }
        .price-unit {
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="index.php"> HotelLinker</a>
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
                        <a class="nav-link" href="#">Tiện Nghi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Liên Hệ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Giới Thiệu</a>
                    </li>
                </ul>
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
</body>
</html> 