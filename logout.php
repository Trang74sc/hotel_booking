<?php
session_start();
session_unset();     // Xóa tất cả biến session
session_destroy();   // Hủy session

// Quay về trang chính
header("Location: index.php");
exit;

