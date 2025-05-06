<?php
session_start(); // Bắt đầu phiên làm việc

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); 
    exit; 
}
$user_id = $_SESSION['user_id']; 
require_once 'config.php';
require_once 'vendor/autoload.php';

use Stripe\Stripe;
use Stripe\Checkout\Session;

$room_id = $_POST['room_id'] ?? $_GET['room_id'] ?? null;
$check_in = $_POST['check_in'] ?? $_GET['check_in'] ?? null;
$check_out = $_POST['check_out'] ?? $_GET['check_out'] ?? null;

if (!$room_id || !$check_in || !$check_out) {
    header('Location: index.php?error=missing_parameters');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM rooms WHERE id = ?");
$stmt->execute([$room_id]);
$room = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$room) {
    header('Location: index.php?error=invalid_room');
    exit;
}

$stmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM bookings 
    WHERE room_id = ? 
    AND (
        (check_in <= ? AND check_out >= ?)
        OR (check_in <= ? AND check_out >= ?)
        OR (check_in >= ? AND check_out <= ?)
    )
");
$stmt->execute([$room_id, $check_in, $check_in, $check_out, $check_out, $check_in, $check_out]);
$booking_exists = $stmt->fetchColumn() > 0;

if ($booking_exists) {
    header('Location: index.php?error=room_not_available');
    exit;
}

$check_in_date = new DateTime($check_in);
$check_out_date = new DateTime($check_out);
$interval = $check_in_date->diff($check_out_date);
$total_nights = $interval->days;
$total_price = $room['price'] * $total_nights;

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['customer_name'])) {
    $customer_name = trim($_POST['customer_name'] ?? '');
    $customer_email = trim($_POST['customer_email'] ?? '');
    $customer_phone = trim($_POST['customer_phone'] ?? '');
    $payment_method = trim($_POST['payment_method'] ?? '');

    // Validate input
    if (empty($customer_name) || empty($customer_email) || empty($customer_phone) || empty($payment_method)) {
        $error = "Vui lòng điền đầy đủ thông tin.";
    } elseif (!filter_var($customer_email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email không hợp lệ. Vui lòng nhập lại.";
    } elseif (!preg_match('/^(0|\+84)[0-9]{9}$/', $customer_phone)) {
        $error = "Số điện thoại không hợp lệ. Vui lòng nhập lại.";
    }

    if (empty($error)) {
        try {
            session_start();
            if (!isset($_SESSION['user_id'])) {
                header("Location: login.php");
                exit;
            }
            $user_id = $_SESSION['user_id'];

           $stmt = $pdo->prepare("
                INSERT INTO bookings (
                    user_id, room_id, customer_name, email,
                    check_in, check_out, status, created_at
                ) VALUES (
                    ?, ?, ?, ?, ?, ?, 'pending', NOW()
                )
            ");
            $stmt->execute([
                $user_id,
                $room_id,
                $customer_name,
                $customer_email,
                $check_in,
                $check_out
            ]);

            $booking_id = $pdo->lastInsertId();

            if ($payment_method === 'stripe') {
                Stripe::setApiKey(STRIPE_SECRET_KEY);
                $checkout_session = Session::create([
                    'payment_method_types' => ['card'],
                    'line_items' => [[
                        'price_data' => [
                            'currency' => 'vnd',
                            'unit_amount' => intval($total_price),
                            'product_data' => [
                                'name' => "Phòng {$room['name']} - {$room['type']}",
                            ],
                        ],
                        'quantity' => 1,
                    ]],
                    'mode' => 'payment',
                    'success_url' => SITE_URL . '/booking_success.php?session_id={CHECKOUT_SESSION_ID}&booking_id=' . $booking_id,
                    'cancel_url' => SITE_URL . "/booking.php?room_id=$room_id&check_in=$check_in&check_out=$check_out",
                ]);
                header("Location: " . $checkout_session->url);
                exit;
            }

        

        } catch (Exception $e) {
            $error = "Đã xảy ra lỗi: " . $e->getMessage();
        }
    }
}


?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Đặt phòng tại HotelLinker - Thanh toán an toàn và nhanh chóng cho kỳ nghỉ sang trọng tại Hà Nội.">
    <title>Đặt Phòng - HotelLinker</title>
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

        /* Booking Details */
        .booking-details {
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            height: 100%;
            transition: transform 0.3s ease;
        }
        .booking-details:hover {
            transform: translateY(-5px);
        }
        .booking-details h2 {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            color: #111827;
            margin-bottom: 20px;
        }
        .booking-details h5 {
            font-family: 'Roboto', sans-serif;
            font-size: 1.2rem;
            font-weight: 600;
            color: #4b5563;
            margin-bottom: 15px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 8px;
        }
        .booking-details .detail-block {
            background: #f9fafb;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .booking-details .detail-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            font-size: 0.95rem;
        }
        .booking-details .detail-item .label {
            font-weight: 500;
            color: #4b5563;
        }
        .booking-details .detail-item .value {
            font-weight: 600;
            color: #111827;
        }
        .booking-details .detail-item .price, .booking-details .total-block .value {
            color: #d4af37;
            font-weight: 700;
        }
        .booking-details .total-block {
            background: #fff;
            border: 2px solid #d4af37;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }
        .booking-details .total-block .label {
            font-size: 1rem;
            font-weight: 600;
            color: #4b5563;
        }
        .booking-details .total-block .value {
            font-size: 1.5rem;
            margin-top: 10px;
        }

        /* Form */
        .search-form {
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            height: 100%;
            transition: transform 0.3s ease;
        }
        .search-form:hover {
            transform: translateY(-5px);
        }
        .search-form h2 {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            color: #111827;
            margin-bottom: 20px;
        }
        .form-label {
            font-weight: 500;
            color: #4b5563;
        }
        .form-control {
            border-radius: 8px;
            font-size: 0.95rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        .form-control:focus {
            border-color: #d4af37;
            box-shadow: 0 0 0 0.2rem rgba(212, 175, 55, 0.25);
        }
        .payment-methods {
            margin-bottom: 20px;
        }
        .payment-method-option {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 8px;
            transition: background 0.3s ease;
        }
        .payment-method-option:hover {
            background: #f9fafb;
        }
        .form-check-input:checked {
            background-color: #d4af37;
            border-color: #d4af37;
        }
        .form-check-label {
            font-size: 1rem;
            color: #4b5563;
            transition: color 0.3s ease;
        }
        .form-check-input:checked + .form-check-label {
            color: #d4af37;
        }

        /* Buttons */
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
        .btn-outline-primary {
            border-color: #d4af37;
            color: #d4af37;
            font-weight: 600;
            border-radius: 8px;
            padding: 12px 20px;
            transition: all 0.3s ease;
        }
        .btn-outline-primary:hover {
            background: #d4af37;
            color: #fff;
            transform: translateY(-3px);
        }

        /* Alert */
        .alert-danger {
            border-radius: 8px;
            font-size: 1rem;
            background: #fee2e2;
            border-color: #fecaca;
            color: #b91c1c;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .booking-details, .search-form {
                padding: 20px;
                margin-bottom: 20px;
            }
            .booking-details h2, .search-form h2 {
                font-size: 1.5rem;
            }
            .booking-details h5 {
                font-size: 1rem;
            }
            .booking-details .total-block .value {
                font-size: 1.2rem;
            }
            .form-control {
                font-size: 0.9rem;
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
                    <li class="nav-item"><a class="nav-link" href="search_rooms.php">Phòng</a></li>
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
        <!-- Error Message -->
        <?php if (!empty($error)): ?>
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

        <div class="row">
            <!-- Booking Details (Left) -->
            <div class="col-md-6">
                <div class="booking-details" data-aos="fade-up" data-aos-duration="1000">
                    <h2 class="mb-4"><i class="bi bi-info-circle me-2"></i>Thông Tin Đặt Phòng</h2>

                    <!-- Room Details Block -->
                    <div class="detail-block">
                        <h5><i class="bi bi-house-door me-2"></i>Chi Tiết Phòng</h5>
                        <div class="detail-item">
                            <span class="label">Phòng</span>
                            <span class="value"><?php echo htmlspecialchars($room['name']); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Loại Phòng</span>
                            <span class="value"><?php echo htmlspecialchars($room['type']); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Giá Mỗi Đêm</span>
                            <span class="value price"><?php echo number_format($room['price'], 0, ',', '.'); ?> VNĐ</span>
                        </div>
                    </div>

                    <!-- Stay Information Block -->
                    <div class="detail-block">
                        <h5><i class="bi bi-calendar-check me-2"></i>Thông Tin Lưu Trú</h5>
                        <div class="detail-item">
                            <span class="label">Ngày Nhận Phòng</span>
                            <span class="value"><?php echo date('d/m/Y', strtotime($check_in)); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Ngày Trả Phòng</span>
                            <span class="value"><?php echo date('d/m/Y', strtotime($check_out)); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Số Đêm</span>
                            <span class="value"><?php echo $total_nights; ?></span>
                        </div>
                    </div>

                    <!-- Total Cost Block -->
                    <div class="total-block">
                        <div class="label"><i class="bi bi-cash-stack me-2"></i>Tổng Chi Phí</div>
                        <div class="value"><?php echo number_format($total_price, 0, ',', '.'); ?> VNĐ</div>
                    </div>
                </div>
            </div>

            <!-- Booking Form (Right) -->
            <div class="col-md-6">
                <form method="POST" class="search-form" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
                    <input type="hidden" name="room_id" value="<?php echo htmlspecialchars($room_id); ?>">
                    <input type="hidden" name="check_in" value="<?php echo htmlspecialchars($check_in); ?>">
                    <input type="hidden" name="check_out" value="<?php echo htmlspecialchars($check_out); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo bin2hex(random_bytes(32)); ?>">

                    <h2 class="mb-4"><i class="bi bi-person me-2"></i>Thông Tin Khách Hàng</h2>
                    <div class="mb-3">
                        <label for="customer_name" class="form-label"><i class="bi bi-person-badge me-2"></i>Họ và Tên</label>
                        <input type="text" class="form-control" id="customer_name" name="customer_name" required aria-describedby="customerNameHelp">
                        <div id="customerNameHelp" class="form-text">Vui lòng nhập đầy đủ họ và tên.</div>
                    </div>
                    <div class="mb-3">
                        <label for="customer_email" class="form-label"><i class="bi bi-envelope me-2"></i>Email</label>
                        <input type="email" class="form-control" id="customer_email" name="customer_email" required aria-describedby="customerEmailHelp">
                        <div id="customerEmailHelp" class="form-text">Chúng tôi sẽ gửi xác nhận đặt phòng qua email này.</div>
                    </div>
                    <div class="mb-3">
                        <label for="customer_phone" class="form-label"><i class="bi bi-telephone me-2"></i>Số Điện Thoại</label>
                        <input type="tel" class="form-control" id="customer_phone" name="customer_phone" required aria-describedby="customerPhoneHelp">
                        <div id="customerPhoneHelp" class="form-text">Vui lòng nhập số điện thoại liên lạc.</div>
                    </div>

                    <h2 class="mb-4"><i class="bi bi-credit-card me-2"></i>Phương Thức Thanh Toán</h2>
                    <div class="payment-methods">
                        <div class="payment-method-option">
                            <input class="form-check-input" type="radio" name="payment_method" id="stripe" value="stripe" checked>
                            <label class="form-check-label ms-2" for="stripe">
                                <i class="bi bi-credit-card me-2"></i>Thanh Toán Bằng Thẻ (Stripe)
                            </label>
                        </div>
                        <div class="payment-method-option">
                            <input class="form-check-input" type="radio" name="payment_method" id="paypal" value="paypal">
                            <label class="form-check-label ms-2" for="paypal">
                                <i class="bi bi-paypal me-2"></i>PayPal
                            </label>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-lock me-2"></i>Tiến Hành Thanh Toán
                        </button>
                        <a href="search_rooms.php?check_in=<?php echo $check_in; ?>&check_out=<?php echo $check_out; ?>" class="btn btn-outline-primary ms-2">
                            <i class="bi bi-arrow-left me-2"></i>Quay Lại
                        </a>
                    </div>
                </form>
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