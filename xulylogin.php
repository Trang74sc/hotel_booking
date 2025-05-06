<?php
session_start();
require_once 'config.php';

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    header('Location: index.php');
    exit();
} else {
    $_SESSION['login_error'] = "Sai email hoặc mật khẩu.";
    header('Location: login.php');
    exit();
}
