<?php
require_once 'config.php';
require_once 'vendor/autoload.php';
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;

if (!isset($_GET['booking_id'])) {
    header('Location: index.php');
    exit;
}

$booking_id = $_GET['booking_id'];

// Lấy thông tin đặt phòng
$stmt = $pdo->prepare("
    SELECT b.*, r.name, r.type 
    FROM bookings b 
    JOIN rooms r ON b.room_id = r.id 
    WHERE b.id = ?
");
$stmt->execute([$booking_id]);
$booking = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$booking) {
    header('Location: index.php');
    exit;
}

// Cập nhật trạng thái thành confirmed
$stmt = $pdo->prepare("UPDATE bookings SET status = 'confirmed' WHERE id = ?");
$stmt->execute([$booking_id]);

// Tạo nội dung cho mã QR
$qr_data = "Mã đặt phòng: " . $booking_id . "\n";
$qr_data .= "Phòng: " . $booking['name'] . "\n";
$qr_data .= "Loại phòng: " . $booking['type'] . "\n";
$qr_data .= "Ngày nhận: " . date('d/m/Y', strtotime($booking['check_in'])) . "\n";
$qr_data .= "Ngày trả: " . date('d/m/Y', strtotime($booking['check_out'])) . "\n";
$qr_data .= "Khách hàng: " . $booking['customer_name'];

// Tạo mã QR
$qr = QrCode::create($qr_data)
    ->setEncoding(new Encoding('UTF-8'))
    ->setErrorCorrectionLevel(new ErrorCorrectionLevelHigh())
    ->setSize(200)
    ->setMargin(10)
    ->setForegroundColor(new Color(0, 0, 0))
    ->setBackgroundColor(new Color(255, 255, 255));

$writer = new PngWriter();
$result = $writer->write($qr);

// Chuyển đổi QR code thành base64 để hiển thị
$qr_base64 = base64_encode($result->getString());

// Tạo thư mục temp nếu chưa tồn tại
if (!file_exists('temp')) {
    mkdir('temp', 0777, true);
}

// Tạo tên file QR code tạm thời
$qr_file = 'temp/qr_' . $booking_id . '.png';

// Lưu QR code vào file
file_put_contents($qr_file, $result->getString());

// Chuẩn bị nội dung email
$email_body = "
    <html>
    <head>
        <style>
            body { font-family: 'Roboto', Arial, sans-serif; color: #111827; }
            .container { padding: 20px; max-width: 600px; margin: 0 auto; }
            h2 { font-family: 'Playfair Display', serif; color: #d4af37; }
            .info { margin: 20px 0; }
            ul { padding-left: 20px; }
            .footer { margin-top: 20px; font-size: 0.9rem; color: #4b5563; }
        </style>
    </head>
    <body>
        <div class='container'>
            <h2>Xác nhận đặt phòng thành công!</h2>
            <p>Cảm ơn bạn đã đặt phòng tại HotelLinker.</p>
            <div class='info'>
                <p><strong>Mã đặt phòng:</strong> $booking_id</p>
                <p><strong>Phòng:</strong> {$booking['name']}</p>
                <p><strong>Loại phòng:</strong> {$booking['type']}</p>
                <p><strong>Ngày nhận phòng:</strong> " . date('d/m/Y', strtotime($booking['check_in'])) . "</p>
                <p><strong>Ngày trả phòng:</strong> " . date('d/m/Y', strtotime($booking['check_out'])) . "</p>
                <p><strong>Khách hàng:</strong> {$booking['customer_name']}</p>
            </div>
            <p>Vui lòng đến nhận phòng đúng ngày và mang theo:</p>
            <ul>
                <li>CMND/CCCD</li>
                <li>Email xác nhận này</li>
                <li>Mã QR đính kèm</li>
            </ul>
            <p>Chúc bạn có kỳ nghỉ vui vẻ!</p>
            <div class='footer'>
                <p>Trân trọng,<br>HotelLinker Team</p>
            </div>
        </div>
    </body>
    </html>
";

// Gửi email xác nhận
try {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = SMTP_HOST;
    $mail->SMTPAuth = true;
    $mail->Username = 'hoangtuongntls@gmail.com';
    $mail->Password = "fskbkzvgbgvplzga";
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = SMTP_PORT;
    $mail->CharSet = 'UTF-8';

    $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
    //$mail->setFrom('hoangtuongntls@gmail.com', 'BBBooking System');
    $mail->addAddress($booking['email']);

    // Đính kèm file QR code
    $mail->addAttachment($qr_file, 'QR_Booking_' . $booking_id . '.png');

    $mail->isHTML(true);
    $mail->Subject = 'Xác nhận đặt phòng thành công - Mã đặt phòng: ' . $booking_id;
    $mail->Body = $email_body;

    $mail->send();
    $email_sent = true;

    // Xóa file QR code tạm thời sau khi gửi
    unlink($qr_file);
} catch (Exception $e) {
    $email_error = $mail->ErrorInfo;
    // Xóa file QR code tạm thời nếu có lỗi
    if (file_exists($qr_file)) {
        unlink($qr_file);
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Xác nhận đặt phòng thành công tại HotelLinker - Xem chi tiết đặt phòng và mã QR.">
    <title>Đặt Phòng Thành Công - HotelLinker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
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

        /* Success Page */
        .success-page {
            min-height: calc(100vh - 160px);
            display: flex;
            align-items: center;
            padding: 40px 0;
        }
        .success-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            padding: 40px;
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
            transition: transform 0.3s ease;
        }
        .success-card:hover {
            transform: translateY(-5px);
        }
        .success-icon {
            width: 80px;
            height: 80px;
            background: #d4af37;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }
        .success-icon i {
            font-size: 40px;
            color: #fff;
        }
        .booking-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            color: #111827;
            margin-bottom: 20px;
        }
        .booking-code {
            background: #f9fafb;
            padding: 15px 30px;
            border-radius: 8px;
            display: inline-block;
            margin: 20px 0;
            font-size: 1.2rem;
            font-weight: 600;
            color: #d4af37;
            border: 1px solid #d4af37;
        }

        /* Booking Details */
        .booking-details {
            background: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: left;
        }
        .booking-details h3 {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            color: #111827;
            margin-bottom: 15px;
        }
        .booking-details .detail-block {
            background: #fff;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
        }
        .booking-details .detail-block h5 {
            font-family: 'Roboto', sans-serif;
            font-size: 1.1rem;
            font-weight: 600;
            color: #4b5563;
            margin-bottom: 10px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 5px;
        }
        .booking-details .detail-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
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

        /* QR Code */
        .qr-code {
            max-width: 200px;
            margin: 20px auto;
        }
        .qr-code img {
            width: 100%;
            height: auto;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }

        /* Email Notification */
        .email-notification {
            background: #fefce8;
            color: #713f12;
            padding: 15px 25px;
            border-radius: 8px;
            margin: 20px 0;
            font-size: 0.95rem;
        }
        .email-notification i {
            margin-right: 10px;
            color: #d4af37;
        }

        /* Buttons */
        .action-buttons .btn {
            padding: 12px 30px;
            font-weight: 600;
            margin: 0 10px;
            border-radius: 8px;
        }
        .btn-primary {
            background: #d4af37;
            border: none;
            transition: background 0.3s ease, transform 0.3s ease;
        }
        .btn-primary:hover {
            background: #b8972f;
            transform: translateY(-3px);
        }
        .btn-outline-primary {
            border-color: #d4af37;
            color: #d4af37;
            transition: all 0.3s ease;
        }
        .btn-outline-primary:hover {
            background: #d4af37;
            color: #fff;
            transform: translateY(-3px);
        }

        /* Print Styles */
        @media print {
            .navbar, .footer {
                display: none;
            }
            .success-page {
                padding: 0;
                min-height: auto;
            }
            .success-card {
                box-shadow: none;
                padding: 20px;
            }
            .action-buttons {
                display: none;
            }
            .email-notification {
                display: none;
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .success-card {
                padding: 20px;
            }
            .booking-title {
                font-size: 2rem;
            }
            .booking-details h3 {
                font-size: 1.2rem;
            }
            .booking-details .detail-item {
                font-size: 0.9rem;
            }
            .qr-code {
                max-width: 150px;
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

    <div class="success-page">
        <div class="container">
            <div class="success-card" data-aos="fade-up" data-aos-duration="1000">
                <div class="success-icon">
                    <i class="bi bi-check-lg"></i>
                </div>
                <h1 class="booking-title">Đặt Phòng Thành Công!</h1>
                <div class="booking-code">
                    <span>Mã đặt phòng: </span>
                    <strong><?php echo htmlspecialchars($booking_id); ?></strong>
                </div>

                <div class="email-notification">
                    <i class="bi bi-envelope-check"></i>
                    Email xác nhận đã được gửi đến: <strong><?php echo htmlspecialchars($booking['email']); ?></strong>
                </div>

                <div class="booking-details">
                    <h3><i class="bi bi-info-circle me-2"></i>Chi Tiết Đặt Phòng</h3>

                    <!-- Room Details Block -->
                    <div class="detail-block">
                        <h5><i class="bi bi-house-door me-2"></i>Chi Tiết Phòng</h5>
                        <div class="detail-item">
                            <span class="label">Phòng</span>
                            <span class="value"><?php echo htmlspecialchars($booking['name']); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Loại Phòng</span>
                            <span class="value"><?php echo htmlspecialchars($booking['type']); ?></span>
                        </div>
                    </div>

                    <!-- Stay Information Block -->
                    <div class="detail-block">
                        <h5><i class="bi bi-calendar-check me-2"></i>Thông Tin Lưu Trú</h5>
                        <div class="detail-item">
                            <span class="label">Ngày Nhận Phòng</span>
                            <span class="value"><?php echo date('d/m/Y', strtotime($booking['check_in'])); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Ngày Trả Phòng</span>
                            <span class="value"><?php echo date('d/m/Y', strtotime($booking['check_out'])); ?></span>
                        </div>
                    </div>

                    <!-- Customer Information Block -->
                    <div class="detail-block">
                        <h5><i class="bi bi-person me-2"></i>Thông Tin Khách Hàng</h5>
                        <div class="detail-item">
                            <span class="label">Họ và Tên</span>
                            <span class="value"><?php echo htmlspecialchars($booking['customer_name']); ?></span>
                        </div>
                        <div class="detail-item">
                            <span class="label">Email</span>
                            <span class="value"><?php echo htmlspecialchars($booking['email']); ?></span>
                        </div>
                    </div>
                </div>

                <div class="qr-code" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
                    <h4 class="mb-3">Mã QR Đặt Phòng</h4>
                    <img src="data:image/png;base64,<?php echo $qr_base64; ?>" alt="Mã QR đặt phòng" loading="lazy">
                </div>

                <div class="action-buttons">
                    <a href="index.php" class="btn btn-outline-primary">
                        <i class="bi bi-house me-2"></i>Về Trang Chủ
                    </a>
                    <a href="#" class="btn btn-primary" onclick="window.print()">
                        <i class="bi bi-printer me-2"></i>In Thông Tin
                    </a>
                </div>
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