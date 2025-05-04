<?php
session_start();
require_once 'config.php';

$name = $_POST['name'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

// Kiểm tra email đã tồn tại chưa
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetch()) {
    header("Location: register.php?error=Email đã được sử dụng");
    exit;
}

// Thêm user mới
$stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
if ($stmt->execute([$name, $email, $password])) {
    $_SESSION['user_id'] = $pdo->lastInsertId();
    $_SESSION['user_name'] = $name;
    header("Location: index.php");
} else {
    header("Location: register.php?error=Đăng ký thất bại");
}
