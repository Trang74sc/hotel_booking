<?php
require_once 'config.php';
session_start();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Phòng không tồn tại.";
    exit;
}

$id = intval($_GET['id']);
$stmt = $pdo->prepare("SELECT * FROM rooms WHERE id = ?");
$stmt->execute([$id]);
$room = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$room) {
    echo "Phòng không tồn tại.";
    exit;
}

// Hàm lấy ảnh theo loại phòng nếu không có ảnh cụ thể
function getRoomImage($type) {
    switch (mb_strtolower($type, 'UTF-8')) {
        case 'đơn': return 'assets/images/phong_don.jpg';
        case 'đôi': return 'assets/images/phong-doi.jpg';
        case 'suite': return 'assets/images/phong_suite.jpeg';
        case 'deluxe': return 'assets/images/phong_deluxe.jpg';
        case 'dorm': return 'assets/images/phong_dorm.jpg';
        case 'superior': return 'assets/images/phong_superior.jpg';
        default: return 'assets/images/rooms/default.jpg';
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi Tiết Phòng | HotelLinker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        .room-image {
            width: 100%;
            max-height: 400px;
            object-fit: cover;
            border-radius: 10px;
        }
    </style>
</head>
<body>
<?php require_once 'header.php'; ?> <!-- Gồm navbar nếu bạn có -->

<div class="container py-5">
    <a href="index.php" class="btn btn-secondary mb-4">← Quay lại danh sách</a>
    <div class="row">
        <div class="col-md-6">
            <?php if (!empty($room['image'])): ?>
                <img src="assets/images/rooms/<?php echo htmlspecialchars($room['image']); ?>" class="room-image" alt="<?php echo htmlspecialchars($room['name']); ?>">
            <?php else: ?>
                <img src="<?php echo getRoomImage($room['type']); ?>" class="room-image" alt="<?php echo htmlspecialchars($room['name']); ?>">
            <?php endif; ?>
        </div>
        <div class="col-md-6">
            <h2><?php echo htmlspecialchars($room['name']); ?></h2>
            <p><strong>Loại:</strong> <?php echo htmlspecialchars($room['type']); ?></p>
            <p><strong>Sức chứa tối đa:</strong> <?php echo htmlspecialchars($room['max_guests']); ?> người</p>
            <p><strong>Giá:</strong> <?php echo number_format($room['price'], 0, ',', '.'); ?> VNĐ/đêm</p>
            <?php if (!empty($room['description'])): ?>
                <p><strong>Mô tả:</strong><br><?php echo nl2br(htmlspecialchars($room['description'])); ?></p>
            <?php endif; ?>
            <a href="booking.php?room_id=<?php echo $room['id']; ?>" class="btn btn-primary mt-3">Đặt phòng ngay</a>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
