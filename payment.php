<?php
require_once 'config.php';

if (!isset($_GET['payment_intent'])) {
    header('Location: index.php');
    exit;
}

$payment_intent_client_secret = $_GET['payment_intent'];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Thanh toán</h1>

        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-body">
                        <form id="payment-form">
                            <div id="payment-element"></div>
                            <button id="submit" class="btn btn-primary w-100 mt-4">
                                <div class="spinner d-none" id="spinner"></div>
                                <span id="button-text">Thanh toán ngay</span>
                            </button>
                            <div id="payment-message" class="d-none"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const stripe = Stripe('your_stripe_publishable_key');
        const elements = stripe.elements({
            clientSecret: '<?php echo $payment_intent_client_secret; ?>'
        });
        const paymentElement = elements.create('payment');
        paymentElement.mount('#payment-element');

        const form = document.getElementById('payment-form');
        const submitButton = document.getElementById('submit');
        const spinner = document.getElementById('spinner');
        const messageDiv = document.getElementById('payment-message');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            // Disable the submit button
            submitButton.disabled = true;
            spinner.classList.remove('d-none');
            
            const {error} = await stripe.confirmPayment({
                elements,
                confirmParams: {
                    return_url: window.location.origin + '/booking_success.php',
                }
            });

            if (error) {
                messageDiv.textContent = error.message;
                messageDiv.classList.remove('d-none');
                submitButton.disabled = false;
                spinner.classList.add('d-none');
            }
        });
    </script>

    <style>
        .spinner {
            width: 20px;
            height: 20px;
            border: 3px solid #ffffff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 1s linear infinite;
            display: inline-block;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 