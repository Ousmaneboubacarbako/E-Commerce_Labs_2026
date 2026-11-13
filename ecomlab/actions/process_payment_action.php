<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../core/paystack_config.php';
require_once __DIR__ . '/../controllers/CartController.php';

// Require login
core_require_login('../view/login.php');

// Get payment reference from Paystack callback
$reference = $_GET['reference'] ?? '';

if (empty($reference)) {
    $_SESSION['error'] = 'Invalid payment reference.';
    header('Location: ../view/payment_failed.php');
    exit;
}

// Verify transaction with Paystack API
$url = 'https://api.paystack.co/transaction/verify/' . $reference;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . PAYSTACK_SECRET_KEY
]);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Check if API call was successful
if ($http_code !== 200) {
    $_SESSION['error'] = 'Unable to verify payment. Please contact support.';
    header('Location: ../view/payment_failed.php');
    exit;
}

// Decode Paystack response
$result = json_decode($response, true);

// Check if payment was successful
if (!$result || !isset($result['status']) || !$result['status']) {
    $_SESSION['error'] = 'Payment verification failed. Please try again.';
    header('Location: ../view/payment_failed.php');
    exit;
}

$data = $result['data'];

// Verify payment status
if ($data['status'] !== 'success') {
    $_SESSION['error'] = 'Payment was not successful. Status: ' . $data['status'];
    header('Location: ../view/payment_failed.php');
    exit;
}

// Get payment details
$amount_paid = $data['amount'] / 100; // Convert from pesewas to GHS
$customer_email = $data['customer']['email'];

// Get cart details
$ip = get_ip();
$cartController = new CartController();
$cartTotal = $cartController->getCartTotal($ip);

// Verify amount matches
if (abs($amount_paid - $cartTotal) > 0.01) { // Allow 1 pesewa difference for rounding
    $_SESSION['error'] = 'Payment amount mismatch. Expected GHS ' . number_format($cartTotal, 2) . 
                         ' but received GHS ' . number_format($amount_paid, 2);
    header('Location: ../view/payment_failed.php');
    exit;
}

// Verify customer email matches
if ($customer_email !== $_SESSION['customer_email']) {
    $_SESSION['error'] = 'Payment email does not match your account.';
    header('Location: ../view/payment_failed.php');
    exit;
}

// All checks passed - process the payment
$customer_id = $_SESSION['customer_id'];
$order_id = $cartController->processPayment($customer_id, $ip, $reference, $amount_paid);

if ($order_id) {
    // Payment processed successfully
    $_SESSION['success'] = 'Payment successful! Your order has been placed.';
    header('Location: ../view/payment_success.php?order_id=' . $order_id);
    exit;
} else {
    // Database error
    $_SESSION['error'] = 'Payment verified but order could not be created. Please contact support with reference: ' . $reference;
    header('Location: ../view/payment_failed.php');
    exit;
}
