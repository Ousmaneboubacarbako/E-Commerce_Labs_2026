<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../core/paystack_config.php';
require_once __DIR__ . '/../controllers/CartController.php';

// Require login to make payment
core_require_login('login.php');

// Redirect admins to home page
if (is_admin()) {
    $_SESSION['error'] = 'Admins cannot make payments.';
    header('Location: ../index.php');
    exit;
}

$ip = get_ip();
$cartController = new CartController();
$cartItems = $cartController->getCartItems($ip);
$cartTotal = $cartController->getCartTotal($ip);

// Redirect if cart is empty
if (empty($cartItems)) {
    $_SESSION['error'] = 'Your cart is empty. Add some products before checkout.';
    header('Location: cart.php');
    exit;
}

// Get customer info
$customer_email = $_SESSION['customer_email'] ?? '';
$customer_name = $_SESSION['customer_name'] ?? '';

// Convert to pesewas (Paystack uses smallest currency unit)
$amount_in_pesewas = $cartTotal * 100;

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment - Shoppn</title>
    <link rel="stylesheet" href="../css/styles.css">
    <script src="https://js.paystack.co/v1/inline.js"></script>
</head>

<body>
    <?php include __DIR__ . '/layout/header.php'; ?>

    <div class="cart-container">
        <h1>Complete Your Payment</h1>

        <div class="checkout-summary">
            <h2>Order Summary</h2>
            <div class="table-wrap">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cartItems as $item): ?>
                            <tr>
                                <td data-label="Product">
                                    <strong><?= htmlspecialchars($item['product_title'], ENT_QUOTES, 'UTF-8') ?></strong>
                                </td>
                                <td data-label="Price">
                                    GHS <?= number_format($item['product_price'], 2) ?>
                                </td>
                                <td data-label="Quantity">
                                    <?= $item['qty'] ?>
                                </td>
                                <td data-label="Subtotal">
                                    <strong>GHS <?= number_format($item['qty'] * $item['product_price'], 2) ?></strong>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="cart-total-row">
                            <td colspan="3" class="text-right"><strong>Total Amount:</strong></td>
                            <td><strong>GHS <?= number_format($cartTotal, 2) ?></strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="payment-info" style="background: #f5f5f5; padding: 20px; margin: 20px 0; border-radius: 8px;">
            <h3>Payment Information</h3>
            <p><strong>Email:</strong> <?= htmlspecialchars($customer_email) ?></p>
            <p><strong>Name:</strong> <?= htmlspecialchars($customer_name) ?></p>
            <p><strong>Amount to Pay:</strong> <span style="font-size: 1.5em; color: #2c3e50;">GHS <?= number_format($cartTotal, 2) ?></span></p>
            
        </div>

        <div class="cart-actions">
            <a href="cart.php" class="btn btn-secondary">Back to Cart</a>
            <button onclick="payWithPaystack()" class="btn btn-success" style="background: #28a745;">
                💳 Pay with Paystack - GHS <?= number_format($cartTotal, 2) ?>
            </button>
        </div>
    </div>

    <script>
        function payWithPaystack() {
            var handler = PaystackPop.setup({
                key: '<?= PAYSTACK_PUBLIC_KEY ?>', // Public key from config
                email: '<?= htmlspecialchars($customer_email, ENT_QUOTES, 'UTF-8') ?>',
                amount: <?= $amount_in_pesewas ?>, // Amount in pesewas
                currency: 'GHS',
                ref: 'TXN_' + Math.floor((Math.random() * 1000000000) + 1), // Generate unique reference
                metadata: {
                    custom_fields: [
                        {
                            display_name: "Customer Name",
                            variable_name: "customer_name",
                            value: "<?= htmlspecialchars($customer_name, ENT_QUOTES, 'UTF-8') ?>"
                        }
                    ]
                },
                callback: function(response) {
                    // Payment successful - verify on server
                    window.location = '../actions/process_payment_action.php?reference=' + response.reference;
                },
                onClose: function() {
                    alert('Payment window closed. You can try again when ready.');
                }
            });
            handler.openIframe();
        }
    </script>

</body>

</html>
