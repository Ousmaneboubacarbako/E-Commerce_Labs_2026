<?php
//
//
//
//
//

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CustomerController.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
    exit;
}

$email = trim($_POST['email'] ?? '');
$pass = trim($_POST['password'] ?? '');

$controller = new CustomerController();
$result = $controller->login($email, $pass);

if ($result === false || isset($result['error'])) {
    $_SESSION['login_error'] = $result['error'] ?? 'Invalid email or password.';
    echo json_encode(['success' => false, 'message' => $_SESSION['login_error']]);
    exit;
}

core_login($result);

echo json_encode(['success' => true, 'message' => 'Login successful.']);
exit;

