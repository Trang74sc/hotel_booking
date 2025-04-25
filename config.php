<?php
// Database configuration
$db_host = 'localhost';
$db_name = 'qlkhachsan';
$db_user = 'root';
$db_pass = '';

try {
    $pdo = new PDO(
        "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4",
        $db_user,
        $db_pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}

// Stripe configuration
define('STRIPE_SECRET_KEY', 'sk_test_51RGhvKEHsCxOfofePc4Hj5CVqWAZYYisSHNjmYCtmiv6BXRrtZaqtNHA9nKypiJ52lGPrl2ZRto9BKBGC930a1gS00q2qnWy2x');
define('STRIPE_PUBLIC_KEY', 'pk_test_51RGhvKEHsCxOfofeTAVahwa2SXDk1rfbPjcoZ4skHPNoW88cm3sK7yyka6Xtl85ghen0QRt0Nv7ur8JIDmsFSjea00tG1IUvpY');

// Email configuration
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'nguyenthithanh2126@gmail.com');
define('SMTP_PASSWORD', 'odtf wxvv iops zpoa'); // Use App Password for Gmail
define('SMTP_FROM_EMAIL', 'nguyenthithanh@gmail.com');
define('SMTP_FROM_NAME', 'Việt Hotel');

// Website configuration
define('SITE_URL', 'http://localhost/trang_bt');
?> 