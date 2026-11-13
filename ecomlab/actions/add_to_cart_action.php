<?php

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CartController.php';

// Require login to add to cart
core_require_login('../view/login.php');

// Prevent admins from adding to cart
if (is_admin()) {
    $_SESSION['error'] = 'Admins cannot add items to cart.';
    $referer = $_SERVER['HTTP_REFERER'] ?? '../view/home.php';
    header('Location: ' . $referer);
    exit;
}

// Get product ID from URL
$product_id = isset($_GET['add_cart']) && is_numeric($_GET['add_cart']) ? (int)$_GET['add_cart'] : 0;

if ($product_id <= 0) {
    $_SESSION['error'] = 'Invalid product.';
    header('Location: ../view/home.php');
    exit;
}

// Get user's IP address
$ip = get_ip();

// Get customer ID if logged in
$customer_id = is_logged_in() ? $_SESSION['customer_id'] : null;

// Create cart controller
$cartController = new CartController();

// Check if product is already in cart
if ($cartController->isInCart($product_id, $ip)) {
    $_SESSION['cart_info'] = 'This item is already in your cart. Go to cart to update quantity.';
    
    // Redirect back to referring page or home
    $referer = $_SERVER['HTTP_REFERER'] ?? '../view/home.php';
    header('Location: ' . $referer);
    exit;
}

// Add to cart (default quantity is 1)
$result = $cartController->addToCart($product_id, 1, $ip, $customer_id);

if ($result) {
    $_SESSION['cart_success'] = 'Product added to cart successfully!';
} else {
    $_SESSION['error'] = 'Failed to add product to cart.';
}

// Redirect back to referring page or home
$referer = $_SERVER['HTTP_REFERER'] ?? '../view/home.php';
header('Location: ' . $referer);
exit;
