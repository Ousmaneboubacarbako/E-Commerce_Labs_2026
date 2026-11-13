<?php

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CustomerController.php';

// Require admin access
core_require_admin('../index.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../view/admin/account.php');
    exit;
}

$customer_id = $_SESSION['customer_id'];
$name = trim($_POST['customer_name'] ?? '');
$email = trim($_POST['customer_email'] ?? '');
$country = trim($_POST['customer_country'] ?? '');
$city = trim($_POST['customer_city'] ?? '');
$contact = trim($_POST['customer_contact'] ?? '');

// Validation
if (empty($name) || empty($email) || empty($country) || empty($city) || empty($contact)) {
    $_SESSION['error'] = 'All fields are required.';
    header('Location: ../view/admin/account.php');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = 'Invalid email format.';
    header('Location: ../view/admin/account.php');
    exit;
}

$controller = new CustomerController();

// Check if email is already taken by another user
if ($controller->checkEmailExists($email, $customer_id)) {
    $_SESSION['error'] = 'Email is already taken by another account.';
    header('Location: ../view/admin/account.php');
    exit;
}

// Handle image upload
$image = null;
if (isset($_FILES['customer_image']) && $_FILES['customer_image']['error'] === UPLOAD_ERR_OK) {
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $filename = $_FILES['customer_image']['name'];
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    
    if (!in_array($ext, $allowed)) {
        $_SESSION['error'] = 'Invalid image format. Only JPG, PNG, GIF, and WEBP are allowed.';
        header('Location: ../view/admin/account.php');
        exit;
    }
    
    // Create customers directory if it doesn't exist
    $upload_dir = __DIR__ . '/../images/customers/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    // Generate unique filename
    $image = uniqid() . '_' . time() . '.' . $ext;
    $upload_path = $upload_dir . $image;
    
    if (!move_uploaded_file($_FILES['customer_image']['tmp_name'], $upload_path)) {
        $_SESSION['error'] = 'Failed to upload image.';
        header('Location: ../view/admin/account.php');
        exit;
    }
}

// Update customer
$result = $controller->updateCustomer($customer_id, $name, $email, $country, $city, $contact, $image);

if ($result) {
    // Update session data
    $_SESSION['customer_name'] = $name;
    $_SESSION['customer_email'] = $email;
    
    $_SESSION['success'] = 'Profile updated successfully!';
} else {
    $_SESSION['error'] = 'Failed to update profile.';
}

header('Location: ../view/admin/account.php');
exit;
