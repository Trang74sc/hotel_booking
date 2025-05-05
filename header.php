<?php
require_once 'config.php';
session_start();
?>
<!-- Navbar -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <script src="https://js.stripe.com/v3/"></script>
<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container">
        <a class="navbar-brand" href="index.php">HotelLinker</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Trang Chủ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="rooms.php">Phòng</a>
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