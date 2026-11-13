<?php

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CustomerController.php';
require_once __DIR__ . '/../classes/CustomerClass.php';

// Require admin access
core_require_admin('../index.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../view/admin/account.php');
    exit;
}

$customer_id = $_SESSION['customer_id'];
$current_password = $_POST['current_password'] ?? '';
$new_password = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// Validation
if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
    $_SESSION['error'] = 'All password fields are required.';
    header('Location: ../view/admin/account.php');
    exit;
}

if ($new_password !== $confirm_password) {
    $_SESSION['error'] = 'New password and confirmation do not match.';
    header('Location: ../view/admin/account.php');
    exit;
}

if (strlen($new_password) < 6) {
    $_SESSION['error'] = 'New password must be at least 6 characters long.';
    header('Location: ../view/admin/account.php');
    exit;
}

// Verify current password
$customerClass = new Customer();
$customer = $customerClass->getCustomerById($customer_id);

if (!$customer) {
    $_SESSION['error'] = 'Admin account not found.';
    header('Location: ../view/admin/account.php');
    exit;
}

// Get full customer data including password
$customerWithPassword = $customerClass->getCustomerByEmail($customer['customer_email']);

if (!password_verify($current_password, $customerWithPassword['customer_pass'])) {
    $_SESSION['error'] = 'Current password is incorrect.';
    header('Location: ../view/admin/account.php');
    exit;
}

// Hash new password
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

// Update password
$controller = new CustomerController();
$result = $controller->updatePassword($customer_id, $hashed_password);

if ($result) {
    $_SESSION['success'] = 'Password updated successfully!';
} else {
    $_SESSION['error'] = 'Failed to update password.';
}

header('Location: ../view/admin/account.php');
exit;
