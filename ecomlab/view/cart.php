<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CartController.php';

// Require login to view cart
core_require_login('login.php');

// Redirect admins to home page (admins shouldn't access cart)
if (is_admin()) {
    $_SESSION['error'] = 'Admins cannot access the shopping cart.';
    header('Location: ../index.php');
    exit;
}

$ip = get_ip();
$cartController = new CartController();
$cartItems = $cartController->getCartItems($ip);
$cartTotal = $cartController->getCartTotal($ip);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Shopping Cart - Shoppn</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body>
    <?php include __DIR__ . '/layout/header.php'; ?>

    <div class="cart-container">
        <h1>Shopping Cart</h1>

        <?php if (isset($_SESSION['cart_success'])): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($_SESSION['cart_success'], ENT_QUOTES, 'UTF-8') ?>
            </div>
            <?php unset($_SESSION['cart_success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['cart_info'])): ?>
            <div class="alert alert-warning">
                <?= htmlspecialchars($_SESSION['cart_info'], ENT_QUOTES, 'UTF-8') ?>
            </div>
            <?php unset($_SESSION['cart_info']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8') ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (empty($cartItems)): ?>
            <div class="empty-cart">
                <div class="empty-cart-icon">🛒</div>
                <h2>Your cart is empty</h2>
                <p>Add some products to get started!</p>
                <a href="../index.php" class="btn btn-primary">Continue Shopping</a>
            </div>
        <?php else: ?>
            <!-- Remove Items Form -->
            <form method="POST" action="../actions/remove_from_cart_action.php" id="removeForm">
                <div class="table-wrap">
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Select</th>
                                <th>Product</th>
                                <th>Image</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cartItems as $item): ?>
                                <tr>
                                    <td data-label="Select">
                                        <input type="checkbox" name="remove[]" value="<?= $item['p_id'] ?>" class="cart-checkbox">
                                    </td>
                                    <td data-label="Product">
                                        <strong><?= htmlspecialchars($item['product_title'], ENT_QUOTES, 'UTF-8') ?></strong>
                                    </td>
                                    <td data-label="Image">
                                        <?php if ($item['product_image']): ?>
                                            <img src="../images/products/<?= htmlspecialchars($item['product_image'], ENT_QUOTES, 'UTF-8') ?>" 
                                                 alt="<?= htmlspecialchars($item['product_title'], ENT_QUOTES, 'UTF-8') ?>"
                                                 class="cart-product-image">
                                        <?php else: ?>
                                            <span class="no-image">No image</span>
                                        <?php endif; ?>
                                    </td>
                                    <td data-label="Price">
                                        $<?= number_format($item['product_price'], 2) ?>
                                    </td>
                                    <td data-label="Quantity">
                                        <span class="qty-display"><?= $item['qty'] ?></span>
                                    </td>
                                    <td data-label="Subtotal">
                                        <strong>$<?= number_format($item['qty'] * $item['product_price'], 2) ?></strong>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr class="cart-total-row">
                                <td colspan="5" class="text-right"><strong>Total:</strong></td>
                                <td><strong>$<?= number_format($cartTotal, 2) ?></strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="cart-actions">
                    <button type="submit" class="btn btn-danger">Remove Selected</button>
                </div>
            </form>

            <!-- Update Quantities Form -->
            <form method="POST" action="../actions/update_qty_action.php" id="updateForm">
                <h2>Update Quantities</h2>
                <div class="table-wrap">
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Current Qty</th>
                                <th>New Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cartItems as $item): ?>
                                <tr>
                                    <td data-label="Product">
                                        <strong><?= htmlspecialchars($item['product_title'], ENT_QUOTES, 'UTF-8') ?></strong>
                                    </td>
                                    <td data-label="Current Qty">
                                        <?= $item['qty'] ?>
                                    </td>
                                    <td data-label="New Qty">
                                        <input type="hidden" name="update[]" value="<?= $item['p_id'] ?>">
                                        <input type="number" 
                                               name="qty[<?= $item['p_id'] ?>]" 
                                               value="<?= $item['qty'] ?>" 
                                               min="1" 
                                               max="99"
                                               class="qty-input">
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="cart-actions">
                    <button type="submit" class="btn btn-primary">Update Quantities</button>
                    <a href="../index.php" class="btn btn-secondary">Continue Shopping</a>
                    <a href="checkout.php" class="btn btn-success">Proceed to Checkout</a>
                </div>
            </form>
        <?php endif; ?>
    </div>
</body>

</html>
