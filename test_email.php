<?php
require_once 'config.php';
require_once 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

try {
    $mail = new PHPMailer(true);
    
    // Debug mode
    $mail->SMTPDebug = 2; // Enable verbose debug output
    
    // Server settings
    $mail->isSMTP();
    $mail->Host = SMTP_HOST;
    $mail->SMTPAuth = true;
    $mail->Username = SMTP_USERNAME;
    $mail->Password = SMTP_PASSWORD;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = SMTP_PORT;
    
    // Additional SMTP settings
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );
    
    $mail->CharSet = 'UTF-8';

    // Recipients
    $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
    $mail->addAddress(SMTP_USERNAME); // Gửi test email đến chính địa chỉ của bạn

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Test Email Configuration';
    $mail->Body = '
        <h2>Test Email Configuration</h2>
        <p>Nếu bạn nhận được email này, cấu hình SMTP đã hoạt động thành công!</p>
        <p>Bạn có thể tiếp tục sử dụng chức năng gửi email trong hệ thống đặt phòng.</p>
    ';

    $mail->send();
    echo "Email test đã được gửi thành công! Vui lòng kiểm tra hộp thư của bạn.";
} catch (Exception $e) {
    echo "Không thể gửi email. Mailer Error: {$mail->ErrorInfo}";
} 