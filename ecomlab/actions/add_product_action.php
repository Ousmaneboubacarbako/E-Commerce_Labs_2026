<?php

require_once __DIR__ . '/../core/core.php';
require_admin('../view/login.php');

require_once __DIR__ . '/../controllers/ProductController.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

// Validate required fields
$category = trim($_POST['product_cat'] ?? '');
$brand = trim($_POST['product_brand'] ?? '');
$title = trim($_POST['product_title'] ?? '');
$price = trim($_POST['product_price'] ?? '');
$desc = trim($_POST['product_desc'] ?? '');
$keywords = trim($_POST['product_keywords'] ?? '');

if (empty($category) || empty($brand) || empty($title) || empty($price)) {
    echo json_encode(['success' => false, 'message' => 'All required fields must be filled.']);
    exit;
}

// Validate price is numeric
if (!is_numeric($price) || $price < 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid price format.']);
    exit;
}

// Handle image upload
$filename = null;
if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
    $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $maxSize = 5 * 1024 * 1024; // 5MB
    
    // Validate file type
    if (!in_array($_FILES['product_image']['type'], $allowed)) {
        echo json_encode(['success' => false, 'message' => 'Invalid file type. Only JPG, PNG, GIF, and WebP are allowed.']);
        exit;
    }
    
    // Validate file size
    if ($_FILES['product_image']['size'] > $maxSize) {
        echo json_encode(['success' => false, 'message' => 'File too large. Maximum size is 5MB.']);
        exit;
    }
    
    // Generate unique filename
    $extension = pathinfo($_FILES['product_image']['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '_' . time() . '.' . $extension;
    $uploadPath = __DIR__ . '/../images/products/' . $filename;
    
    // Move uploaded file
    if (!move_uploaded_file($_FILES['product_image']['tmp_name'], $uploadPath)) {
        echo json_encode(['success' => false, 'message' => 'Failed to upload image.']);
        exit;
    }
}

// Sanitize inputs
$title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
$desc = htmlspecialchars($desc, ENT_QUOTES, 'UTF-8');
$keywords = htmlspecialchars($keywords, ENT_QUOTES, 'UTF-8');

$controller = new ProductController();

try {
    $result = $controller->addProduct($category, $brand, $title, $price, $desc, $filename, $keywords);
    
    if ($result) {
        $_SESSION['success'] = 'Product added successfully.';
        echo json_encode(['success' => true, 'message' => 'Product added successfully.']);
    } else {
        // Delete uploaded file if database insert failed
        if ($filename && file_exists(__DIR__ . '/../images/products/' . $filename)) {
            unlink(__DIR__ . '/../images/products/' . $filename);
        }
        echo json_encode(['success' => false, 'message' => 'Failed to add product.']);
    }
} catch (Exception $e) {
    // Delete uploaded file on error
    if ($filename && file_exists(__DIR__ . '/../images/products/' . $filename)) {
        unlink(__DIR__ . '/../images/products/' . $filename);
    }
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
