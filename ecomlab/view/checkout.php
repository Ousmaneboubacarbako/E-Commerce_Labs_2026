<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CartController.php';

// Require login to checkout
core_require_login('login.php');

// Redirect admins to home page (admins shouldn't checkout)
if (is_admin()) {
    $_SESSION['error'] = 'Admins cannot access checkout.';
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
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Checkout - Shoppn</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body>
    <?php include __DIR__ . '/layout/header.php'; ?>

    <div class="cart-container">
        <h1>Checkout</h1>

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
                                    $<?= number_format($item['product_price'], 2) ?>
                                </td>
                                <td data-label="Quantity">
                                    <?= $item['qty'] ?>
                                </td>
                                <td data-label="Subtotal">
                                    <strong>$<?= number_format($item['qty'] * $item['product_price'], 2) ?></strong>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="cart-total-row">
                            <td colspan="3" class="text-right"><strong>Total:</strong></td>
                            <td><strong>$<?= number_format($cartTotal, 2) ?></strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="checkout-notice" style="background: #e8f4f8; padding: 20px; border-radius: 8px; margin: 20px 0;">
            <h3>✓ Review Your Order</h3>
            <p>Please review your order details above. Click "Proceed to Payment" to complete your purchase securely with Paystack.</p>
        </div>

        <div class="cart-actions">
            <a href="cart.php" class="btn btn-secondary">Back to Cart</a>
            <a href="payment.php" class="btn btn-success" style="background: #28a745;">Proceed to Payment →</a>
        </div>
    </div>
</body>

</html>
