<?php

require_once __DIR__ . '/../core/core.php';
require_admin('../view/login.php');

require_once __DIR__ . '/../controllers/ProductController.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$brandName = trim($_POST['brand_name'] ?? '');

if (empty($brandName)) {
    echo json_encode(['success' => false, 'message' => 'Brand name is required.']);
    exit;
}

$brandName = htmlspecialchars($brandName, ENT_QUOTES, 'UTF-8');

$controller = new ProductController();

try {
    $result = $controller->addBrand($brandName);
    
    if ($result) {
        $_SESSION['success'] = 'Brand added successfully.';
        echo json_encode(['success' => true, 'message' => 'Brand added successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add brand.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
