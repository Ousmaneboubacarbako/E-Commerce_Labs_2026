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
$productId = trim($_POST['product_id'] ?? '');
$category = trim($_POST['product_cat'] ?? '');
$brand = trim($_POST['product_brand'] ?? '');
$title = trim($_POST['product_title'] ?? '');
$price = trim($_POST['product_price'] ?? '');
$desc = trim($_POST['product_desc'] ?? '');
$keywords = trim($_POST['product_keywords'] ?? '');
$existingImage = trim($_POST['existing_image'] ?? '');

if (empty($productId) || !is_numeric($productId)) {
    echo json_encode(['success' => false, 'message' => 'Invalid product ID.']);
    exit;
}

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
$filename = $existingImage; // Keep existing image by default
$oldImageToDelete = null;

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
    
    // Mark old image for deletion
    if ($existingImage) {
        $oldImageToDelete = $existingImage;
    }
}

// Sanitize inputs
$title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
$desc = htmlspecialchars($desc, ENT_QUOTES, 'UTF-8');
$keywords = htmlspecialchars($keywords, ENT_QUOTES, 'UTF-8');

$controller = new ProductController();

try {
    $result = $controller->updateProduct($productId, $category, $brand, $title, $price, $desc, $filename, $keywords);
    
    if ($result) {
        // Delete old image if update was successful and new image was uploaded
        if ($oldImageToDelete) {
            $oldPath = __DIR__ . '/../images/products/' . $oldImageToDelete;
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }
        
        $_SESSION['success'] = 'Product updated successfully.';
        echo json_encode(['success' => true, 'message' => 'Product updated successfully.']);
    } else {
        // Delete new uploaded file if database update failed
        if ($filename !== $existingImage && file_exists(__DIR__ . '/../images/products/' . $filename)) {
            unlink(__DIR__ . '/../images/products/' . $filename);
        }
        echo json_encode(['success' => false, 'message' => 'Failed to update product.']);
    }
} catch (Exception $e) {
    // Delete new uploaded file on error
    if ($filename !== $existingImage && file_exists(__DIR__ . '/../images/products/' . $filename)) {
        unlink(__DIR__ . '/../images/products/' . $filename);
    }
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
