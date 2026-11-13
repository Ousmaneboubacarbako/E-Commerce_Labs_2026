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

// Get items to remove
$itemsToRemove = $_POST['remove'] ?? [];

if (empty($itemsToRemove)) {
    $_SESSION['error'] = 'No items selected for removal.';
    header('Location: ../view/cart.php');
    exit;
}

$removed = 0;
foreach ($itemsToRemove as $product_id) {
    // Validate that product_id is a positive integer
    if (is_numeric($product_id) && $product_id > 0) {
        if ($cartController->removeFromCart((int)$product_id, $ip)) {
            $removed++;
        }
    }
}

if ($removed > 0) {
    $_SESSION['cart_success'] = "$removed item(s) removed from cart.";
} else {
    $_SESSION['error'] = 'Failed to remove items from cart.';
}

header('Location: ../view/cart.php');
exit;
