<?php
require_once 'config.php';
session_start();
// Fetch all rooms
$stmt = $pdo->query("SELECT * FROM rooms");
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

function getRoomImage($type) {
    switch (strtolower($type)) {
        case 'Đơn': return 'assets/images/phong_don.jpg';
         
        case 'Đôi':return 'assets/images/phong-doi.jpg';
        
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
    <title>HotelLinker - Trang Chủ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        .hero-section {
            background-image: url('assets/images/header.jpg');
            background-size: cover;
            background-position: center;
            padding: 100px 0;
            position: relative;
        }
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
        }
        .search-box {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 1;
        }
        .room-card {
            border: none;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            border-radius: 10px;
            overflow: hidden;
        }
        .room-card img {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }
        .room-info {
            padding: 20px;
        }
        .room-title {
            font-size: 1.5rem;
            margin-bottom: 15px;
        }
        .room-features {
            margin-bottom: 15px;
        }
        .room-price {
            font-size: 1.25rem;
            color: #2563eb;
            font-weight: bold;
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
        .search-btn {
            background-color: #10b981;
            border-color: #10b981;
        }
        .search-btn:hover {
            background-color: #059669;
            border-color: #059669;
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
                        <a class="nav-link active" href="#">Trang Chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Phòng</a>
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

    <!-- Hero Section with Search -->
    <section class="hero-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="search-box">
                    <h3 class="text-center mb-4">Tìm Phòng</h3>

                        <?php if (isset($_SESSION['user_id'])): ?>
                            <form action="search_rooms.php" method="GET" id="searchForm">
                                <div class="row g-3"> 
                                    <div class="col-md-3">
                                        <label class="form-label">Ngày Nhận Phòng</label>
                                        <input type="date" class="form-control" name="check_in" id="check_in" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Ngày Trả Phòng</label>
                                        <input type="date" class="form-control" name="check_out" id="check_out" required>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Người Lớn</label>
                                        <select class="form-select" name="adults" required>
                                            <?php for($i = 1; $i <= 5; $i++): ?>
                                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Trẻ Em</label>
                                        <select class="form-select" name="children">
                                            <?php for($i = 0; $i <= 3; $i++): ?>
                                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">&nbsp;</label>
                                        <button type="submit" class="btn btn-primary search-btn w-100">
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
            </div>
        </div>
    </section>

    <!-- Room Listings -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">PHÒNG</h2>
            <div class="row">
                <?php foreach ($rooms as $room): ?>
                    <div class="col-md-4">
                        <div class="room-card">
                            <?php if (!empty($room['image'])): ?>
                                <img src="assets/images/rooms/<?php echo htmlspecialchars($room['image']); ?>" 
                                     class="room-image" 
                                     alt="<?php echo htmlspecialchars($room['name']); ?>"
                                     style="width: 100%; height: 250px; object-fit: cover;">
                            <?php else: ?>
                                <img src="<?php echo getRoomImage($room['type']); ?>" 
                                     class="room-image" 
                                     alt="<?php echo htmlspecialchars($room['name']); ?>"
                                     style="width: 100%; height: 250px; object-fit: cover;">
                            <?php endif; ?>
                            <div class="room-info">
                                <h3 class="room-title"><?php echo htmlspecialchars($room['name']); ?></h3>
                                <div class="room-features">
                                    <div class="mb-2">
                                        <i class="bi bi-people me-2"></i>Tối đa: <?php echo htmlspecialchars($room['max_guests']); ?> người
                                    </div>
                                    <div>
                                        <i class="bi bi-house-door me-2"></i>Loại: <?php echo htmlspecialchars($room['type']); ?>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="room-price"><?php echo number_format($room['price'], 0, ',', '.'); ?> VNĐ/đêm</div>
                                    <a href="room_details.php?id=<?php echo $room['id']; ?>" class="btn btn-outline-primary">Chi tiết</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <script>
    // Set minimum date for check-in to today
    const today = new Date().toISOString().split('T')[0];
    const checkInInput = document.getElementById('check_in');
    const checkOutInput = document.getElementById('check_out');
    const searchForm = document.getElementById('searchForm');

    // Set minimum date for check-in
    checkInInput.min = today;
    
    // Update check-out minimum date when check-in changes
    checkInInput.addEventListener('change', function() {
        const checkInDate = new Date(this.value);
        const nextDay = new Date(checkInDate);
        nextDay.setDate(checkInDate.getDate() + 1);
        
        checkOutInput.min = nextDay.toISOString().split('T')[0];
        
        if (checkOutInput.value && new Date(checkOutInput.value) <= checkInDate) {
            checkOutInput.value = nextDay.toISOString().split('T')[0];
        }
    });

    // Form validation before submit
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
        
        this.submit();
    });
    </script>
     <?php require_once 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 
