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
    ->setSize(300)
    ->setMargin(10)
    ->setForegroundColor(new Color(0, 0, 0))
    ->setBackgroundColor(new Color(255, 255, 255));

$writer = new PngWriter();
$result = $writer->write($qr);

// Chuyển đổi QR code thành base64
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
            body { font-family: Arial, sans-serif; }
            .container { padding: 20px; }
            .info { margin: 20px 0; }
            ul { padding-left: 20px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <h2>Xác nhận đặt phòng thành công!</h2>
            <p>Cảm ơn bạn đã đặt phòng tại khách sạn chúng tôi.</p>

            <p>Vui lòng đến nhận phòng đúng ngày và mang theo:</p>
            <ul>
                <li>CMND/CCCD</li>
                <li>Email xác nhận này</li>
                <li>Mã QR đính kèm</li>
            </ul>

            <p>Chúc bạn có kỳ nghỉ vui vẻ!</p>
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
    $mail->Username = SMTP_USERNAME;
    $mail->Password = SMTP_PASSWORD;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = SMTP_PORT;
    $mail->CharSet = 'UTF-8';

    $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
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
    <title>Đặt phòng thành công - HotelLinker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        /* Navbar styles */
        .navbar {
            background-color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 0;
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
            color: #2563eb;
        }
        .nav-link {
            color: #1f2937;
            font-weight: 500;
            padding: 0.5rem 1rem;
        }
        .nav-link:hover {
            color: #2563eb;
        }

        /* Success page styles */
        .success-page {
            min-height: calc(100vh - 160px); /* Adjust for header and footer */
            display: flex;
            align-items: center;
            background-color: #f8fafc;
            padding: 60px 0;
        }
        .success-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(44, 62, 80, 0.15);
            padding: 40px;
            text-align: center;
            max-width: 800px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }
        .success-icon {
            width: 100px;
            height: 100px;
            background: #10b981;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
        }
        .success-icon i {
            font-size: 50px;
            color: white;
        }
        .booking-title {
            color: #10b981;
            font-size: 2.5rem;
            margin-bottom: 20px;
            font-weight: 600;
        }
        .booking-code {
            background: #f1f5f9;
            padding: 15px 30px;
            border-radius: 10px;
            display: inline-block;
            margin: 20px 0;
            font-size: 1.2rem;
            font-weight: 600;
            color: #334155;
        }
        .booking-details {
            background: #f8fafc;
            padding: 30px;
            border-radius: 15px;
            margin: 30px 0;
            text-align: left;
        }
        .booking-details h3 {
            color: #334155;
            font-size: 1.5rem;
            margin-bottom: 20px;
            font-weight: 600;
        }
        .detail-item {
            margin-bottom: 15px;
            display: flex;
            align-items: flex-start;
        }
        .detail-item i {
            color: #10b981;
            margin-right: 10px;
            font-size: 1.2rem;
            margin-top: 3px;
        }
        .qr-code {
            max-width: 200px;
            margin: 30px auto;
        }
        .qr-code img {
            width: 100%;
            height: auto;
        }
        .action-buttons {
            margin-top: 30px;
        }
        .action-buttons .btn {
            padding: 12px 30px;
            font-weight: 600;
            margin: 0 10px;
            border-radius: 10px;
        }
        .email-notification {
            background: #e0f2fe;
            color: #0369a1;
            padding: 15px 25px;
            border-radius: 10px;
            margin: 20px 0;
        }
        .email-notification i {
            margin-right: 10px;
        }

        /* Print styles */
        @media print {
            .navbar, .site-footer {
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
        }
    </style>
</head>
<body>
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

    <div class="success-page">
        <div class="container">
            <div class="success-card">
                <div class="success-icon">
                    <i class="bi bi-check-lg"></i>
                </div>
                
                <h1 class="booking-title">Đặt phòng thành công!</h1>
                
                <div class="booking-code">
                    <span>Mã đặt phòng của bạn là: </span>
                    <strong><?php echo isset($_GET['booking_id']) ? $_GET['booking_id'] : '36'; ?></strong>
                </div>

                <div class="email-notification">
                    <i class="bi bi-envelope-check"></i>
                    Email xác nhận đã được gửi đến địa chỉ: 
                    <strong><?php echo isset($_GET['email']) ? $_GET['email'] : 'trangbuisc0704@gmail.com'; ?></strong>
                </div>

                <div class="booking-details">
                    <h3><i class="bi bi-info-circle"></i> Chi tiết đặt phòng:</h3>
                    <div class="detail-item">
                        <i class="bi bi-building"></i>
                        <div>
                            <strong>Phòng:</strong> <?php echo isset($_GET['room_name']) ? $_GET['room_name'] : 'Phòng Superior Cao Cấp'; ?>
                        </div>
                    </div>
                    <div class="detail-item">
                        <i class="bi bi-door-open"></i>
                        <div>
                            <strong>Loại phòng:</strong> <?php echo isset($_GET['room_type']) ? $_GET['room_type'] : 'Superior'; ?>
                        </div>
                    </div>
                    <div class="detail-item">
                        <i class="bi bi-calendar-check"></i>
                        <div>
                            <strong>Ngày nhận phòng:</strong> <?php echo isset($_GET['check_in']) ? $_GET['check_in'] : '01/05/2025'; ?>
                        </div>
                    </div>
                    <div class="detail-item">
                        <i class="bi bi-calendar-x"></i>
                        <div>
                            <strong>Ngày trả phòng:</strong> <?php echo isset($_GET['check_out']) ? $_GET['check_out'] : '02/05/2025'; ?>
                        </div>
                    </div>
                </div>

                <div class="qr-code">
                    <h4 class="mb-3">Mã QR đặt phòng của bạn:</h4>
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=BOOKING-<?php echo isset($_GET['booking_id']) ? $_GET['booking_id'] : '36'; ?>" 
                         alt="Mã QR đặt phòng">
                </div>

                <div class="action-buttons">
                    <a href="index.php" class="btn btn-outline-primary">
                        <i class="bi bi-house"></i> Về trang chủ
                    </a>
                    <a href="#" class="btn btn-success" onclick="window.print()">
                        <i class="bi bi-printer"></i> In thông tin
                    </a>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 