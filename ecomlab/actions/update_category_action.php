<?php

require_once __DIR__ . '/../core/core.php';
require_admin('../view/login.php');

require_once __DIR__ . '/../controllers/ProductController.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$categoryId = trim($_POST['category_id'] ?? '');
$categoryName = trim($_POST['category_name'] ?? '');

if (empty($categoryId) || !is_numeric($categoryId)) {
    echo json_encode(['success' => false, 'message' => 'Invalid category ID.']);
    exit;
}

if (empty($categoryName)) {
    echo json_encode(['success' => false, 'message' => 'Category name is required.']);
    exit;
}

$categoryName = htmlspecialchars($categoryName, ENT_QUOTES, 'UTF-8');

$controller = new ProductController();

try {
    $result = $controller->updateCategory($categoryId, $categoryName);
    
    if ($result) {
        $_SESSION['success'] = 'Category updated successfully.';
        echo json_encode(['success' => true, 'message' => 'Category updated successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update category.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
