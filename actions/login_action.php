<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../src/config/config.php';
require_once __DIR__ . '/../src/classes/Auth.php';
require_once __DIR__ . '/../src/helpers/Security.php';
require_once __DIR__ . '/../src/helpers/Validator.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$csrf_token = $_POST['csrf_token'] ?? '';
if (!Security::validateCSRFToken($csrf_token)) {
    echo json_encode(['success' => false, 'message' => 'CSRF validation failed']);
    exit;
}

$email = Validator::sanitize($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

$result = Auth::login($email, $password);
echo json_encode($result);
?>