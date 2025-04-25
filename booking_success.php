<?php
require_once 'config.php';
require_once 'vendor/autoload.php';

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
    <title>Đặt phòng thành công</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h1 class="card-title text-success mb-4">
                            <i class="bi bi-check-circle-fill"></i> Đặt phòng thành công!
                        </h1>
                        <div class="alert alert-success">
                            Mã đặt phòng của bạn là: <strong><?php echo $booking_id; ?></strong>
                        </div>
                        <?php if (isset($email_sent)): ?>
                        <div class="alert alert-info mb-4">
                            <i class="bi bi-envelope-check"></i>
                            Email xác nhận đã được gửi đến địa chỉ: <strong><?php echo htmlspecialchars($booking['email']); ?></strong>
                        </div>
                        <?php elseif (isset($email_error)): ?>
                        <div class="alert alert-warning mb-4">
                            <i class="bi bi-envelope-exclamation"></i>
                            Không thể gửi email xác nhận. Vui lòng liên hệ với chúng tôi để được hỗ trợ.
                        </div>
                        <?php endif; ?>
                        <div class="text-start mb-4">
                            <h5>Chi tiết đặt phòng:</h5>
                            <p>Phòng: <?php echo htmlspecialchars($booking['name']); ?></p>
                            <p>Loại phòng: <?php echo htmlspecialchars($booking['type']); ?></p>
                            <p>Ngày nhận phòng: <?php echo date('d/m/Y', strtotime($booking['check_in'])); ?></p>
                            <p>Ngày trả phòng: <?php echo date('d/m/Y', strtotime($booking['check_out'])); ?></p>
                        </div>
                        
                        <!-- Hiển thị QR code trên trang web -->
                        <div class="text-center mb-4">
                            <h5>Mã QR đặt phòng của bạn:</h5>
                            <img src="data:image/png;base64,<?php echo $qr_base64; ?>" 
                                 alt="Mã QR đặt phòng" 
                                 style="width: 300px; height: 300px; margin: 20px auto;">
                        </div>

                        <div class="alert alert-info">
                            <p class="mb-0">Vui lòng đến nhận phòng đúng ngày và mang theo:</p>
                            <ul class="text-start mb-0">
                                <li>CMND/CCCD</li>
                                <li>Email xác nhận đặt phòng</li>
                                <li>Mã đặt phòng</li>
                            </ul>
                        </div>
                        <a href="index.php" class="btn btn-primary mt-3">Quay về trang chủ</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 