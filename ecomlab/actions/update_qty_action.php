<?php

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CartController.php';

// POST only
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../view/cart.php');
    exit;
}

$ip = get_ip();
$cartController = new CartController();

// Get quantities and product IDs
$quantities = $_POST['qty'] ?? [];
$productIds = $_POST['update'] ?? [];

if (empty($quantities) || empty($productIds)) {
    $_SESSION['error'] = 'No quantities to update.';
    header('Location: ../view/cart.php');
    exit;
}

$updated = 0;
foreach ($productIds as $product_id) {
    // Validate product_id is a positive integer
    if (!is_numeric($product_id) || $product_id <= 0) {
        continue;
    }
    
    $product_id = (int)$product_id;
    $new_qty = $quantities[$product_id] ?? 0;
    
    // Validate quantity is a positive integer and >= 1
    if (is_numeric($new_qty) && $new_qty >= 1 && $new_qty <= 99) {
        if ($cartController->updateCartQty($product_id, $ip, (int)$new_qty)) {
            $updated++;
        }
    }
}

if ($updated > 0) {
    $_SESSION['cart_success'] = 'Cart quantities updated successfully.';
} else {
    $_SESSION['error'] = 'Failed to update cart quantities.';
}

header('Location: ../view/cart.php');
exit;
