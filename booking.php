<?php
require_once 'config.php';
require_once 'vendor/autoload.php';

use Stripe\Stripe;
use Stripe\Checkout\Session;

$room_id = $_POST['room_id'] ?? $_GET['room_id'] ?? null;
$check_in = $_POST['check_in'] ?? $_GET['check_in'] ?? null;
$check_out = $_POST['check_out'] ?? $_GET['check_out'] ?? null;

if (!$room_id || !$check_in || !$check_out) {
    header('Location: index.php?error=missing_parameters');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM rooms WHERE id = ?");
$stmt->execute([$room_id]);
$room = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$room) {
    header('Location: index.php?error=invalid_room');
    exit;
}

$stmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM bookings 
    WHERE room_id = ? 
    AND (
        (check_in <= ? AND check_out >= ?)
        OR (check_in <= ? AND check_out >= ?)
        OR (check_in >= ? AND check_out <= ?)
    )
");
$stmt->execute([$room_id, $check_in, $check_in, $check_out, $check_out, $check_in, $check_out]);
$booking_exists = $stmt->fetchColumn() > 0;

if ($booking_exists) {
    header('Location: index.php?error=room_not_available');
    exit;
}

$check_in_date = new DateTime($check_in);
$check_out_date = new DateTime($check_out);
$interval = $check_in_date->diff($check_out_date);
$total_nights = $interval->days;
$total_price = $room['price'] * $total_nights;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['customer_name'])) {
    $customer_name = trim($_POST['customer_name'] ?? '');
    $customer_email = trim($_POST['customer_email'] ?? '');
    $customer_phone = trim($_POST['customer_phone'] ?? '');
    $payment_method = trim($_POST['payment_method'] ?? '');

    if (empty($customer_name) || empty($customer_email) || empty($customer_phone) || empty($payment_method)) {
        $error = "Vui lòng điền đầy đủ thông tin";
    } else {
        try {
            session_start();
            if (!isset($_SESSION['user_id'])) {
                header("Location: login.php");
                exit;
            }
            $user_id = $_SESSION['user_id'];

            $stmt = $pdo->prepare("
                INSERT INTO bookings (
                    user_id, room_id, customer_name, email,
                    check_in, check_out, status, created_at
                ) VALUES (
                    ?, ?, ?, ?, ?, ?, 'pending', NOW()
                )
            ");

            $stmt->execute([
                $user_id,
                $room_id,
                $customer_name,
                $customer_email,
                $check_in,
                $check_out
            ]);

            $booking_id = $pdo->lastInsertId();

            if ($payment_method === 'stripe') {
                Stripe::setApiKey(STRIPE_SECRET_KEY);
                $checkout_session = Session::create([
                    'payment_method_types' => ['card'],
                    'line_items' => [[
                        'price_data' => [
                            'currency' => 'vnd',
                            'unit_amount' => intval($total_price),
                            'product_data' => [
                                'name' => "Phòng {$room['name']} - {$room['type']}",
                            ],
                        ],
                        'quantity' => 1,
                    ]],
                    'mode' => 'payment',
                    'success_url' => SITE_URL . '/booking_success.php?session_id={CHECKOUT_SESSION_ID}&booking_id=' . $booking_id,
                    'cancel_url' => SITE_URL . "/booking.php?room_id=$room_id&check_in=$check_in&check_out=$check_out",
                ]);

                header("Location: " . $checkout_session->url);
                exit;
            }
            // Xử lý PayPal nếu cần
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
}
?>

<?php require_once 'header.php'; ?>

<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="booking-details fade-in">
                <h2 class="mb-4">
                    <i class="bi bi-info-circle me-2"></i>
                    Thông tin phòng
                </h2>
                <div class="row">
                    <div class="col-md-6">
                        <dl>
                            <dt><i class="bi bi-house-door me-2"></i>Phòng</dt>
                            <dd><?= htmlspecialchars($room['name']); ?></dd>
                            <dt><i class="bi bi-tag me-2"></i>Loại phòng</dt>
                            <dd><?= htmlspecialchars($room['type']); ?></dd>
                            <dt><i class="bi bi-currency-dollar me-2"></i>Giá mỗi đêm</dt>
                            <dd class="price"><?= number_format($room['price'], 0, ',', '.'); ?> VNĐ</dd>
                        </dl>
                    </div>
                    <div class="col-md-6">
                        <dl>
                            <dt><i class="bi bi-calendar-check me-2"></i>Ngày nhận phòng</dt>
                            <dd><?= date('d/m/Y', strtotime($check_in)); ?></dd>
                            <dt><i class="bi bi-calendar-x me-2"></i>Ngày trả phòng</dt>
                            <dd><?= date('d/m/Y', strtotime($check_out)); ?></dd>
                            <dt><i class="bi bi-moon me-2"></i>Số đêm</dt>
                            <dd><?= $total_nights; ?></dd>
                            <dt><i class="bi bi-cash-stack me-2"></i>Tổng tiền</dt>
                            <dd class="price"><?= number_format($total_price, 0, ',', '.'); ?> VNĐ</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger fade-in">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <?= $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="search-form fade-in">
                <input type="hidden" name="room_id" value="<?= htmlspecialchars($room_id); ?>">
                <input type="hidden" name="check_in" value="<?= htmlspecialchars($check_in); ?>">
                <input type="hidden" name="check_out" value="<?= htmlspecialchars($check_out); ?>">

                <h2 class="mb-4">
                    <i class="bi bi-person me-2"></i>
                    Thông tin khách hàng
                </h2>
                <div class="mb-3">
                    <label for="customer_name" class="form-label"><i class="bi bi-person-badge me-2"></i>Họ và tên</label>
                    <input type="text" class="form-control" id="customer_name" name="customer_name" required>
                </div>
                <div class="mb-3">
                    <label for="customer_email" class="form-label"><i class="bi bi-envelope me-2"></i>Email</label>
                    <input type="email" class="form-control" id="customer_email" name="customer_email" required>
                </div>
                <div class="mb-3">
                    <label for="customer_phone" class="form-label"><i class="bi bi-telephone me-2"></i>Số điện thoại</label>
                    <input type="tel" class="form-control" id="customer_phone" name="customer_phone" required>
                </div>

                <h2 class="mb-4"><i class="bi bi-credit-card me-2"></i>Phương thức thanh toán</h2>
                <div class="payment-methods">
                    <div class="payment-method-option">
                        <input class="form-check-input" type="radio" name="payment_method" id="stripe" value="stripe" checked>
                        <label class="form-check-label ms-2" for="stripe">
                            <i class="bi bi-credit-card me-2"></i>Thanh toán bằng thẻ (Stripe)
                        </label>
                    </div>
                    <div class="payment-method-option">
                        <input class="form-check-input" type="radio" name="payment_method" id="paypal" value="paypal">
                        <label class="form-check-label ms-2" for="paypal">
                            <i class="bi bi-paypal me-2"></i>PayPal
                        </label>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-lock me-2"></i>Tiến hành thanh toán
                    </button>
                    <a href="search_rooms.php?check_in=<?= $check_in; ?>&check_out=<?= $check_out; ?>" class="btn btn-secondary ms-2">
                        <i class="bi bi-arrow-left me-2"></i>Quay lại
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
