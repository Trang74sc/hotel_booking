<?php
session_start();
require_once 'config.php';

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = password_hash($_POST['password'] ?? '', PASSWORD_DEFAULT);

// Kiểm tra email đã tồn tại chưa
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetch()) {
    $_SESSION['register_error'] = "Email đã được sử dụng.";
    header("Location: register.php");
    exit;
}

// Thêm user mới
$stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
if ($stmt->execute([$name, $email, $password])) {
    $_SESSION['user_id'] = $pdo->lastInsertId();
    $_SESSION['user_name'] = $name;
    $_SESSION['register_success'] = "Đăng ký thành công!";
    header("Location: index.php");
} else {
    $_SESSION['register_error'] = "Đăng ký thất bại. Vui lòng thử lại.";
    header("Location: register.php");
}
