<?php

require_once __DIR__ . '/../core/core.php';
require_admin('../view/login.php');

require_once __DIR__ . '/../controllers/ProductController.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$brandId = trim($_POST['brand_id'] ?? '');
$brandName = trim($_POST['brand_name'] ?? '');

if (empty($brandId) || !is_numeric($brandId)) {
    echo json_encode(['success' => false, 'message' => 'Invalid brand ID.']);
    exit;
}

if (empty($brandName)) {
    echo json_encode(['success' => false, 'message' => 'Brand name is required.']);
    exit;
}

$brandName = htmlspecialchars($brandName, ENT_QUOTES, 'UTF-8');

$controller = new ProductController();

try {
    $result = $controller->updateBrand($brandId, $brandName);
    
    if ($result) {
        $_SESSION['success'] = 'Brand updated successfully.';
        echo json_encode(['success' => true, 'message' => 'Brand updated successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update brand.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
