<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../src/config/config.php';
require_once __DIR__ . '/../src/classes/Auth.php';
require_once __DIR__ . '/../src/classes/Client.php';
require_once __DIR__ . '/../src/helpers/Security.php';
require_once __DIR__ . '/../src/helpers/Validator.php';

Auth::requireLogin();
Auth::requirePermission('create_client');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$csrf_token = $_POST['csrf_token'] ?? '';
if (!Security::validateCSRFToken($csrf_token)) {
    echo json_encode(['success' => false, 'message' => 'CSRF validation failed']);
    exit;
}

$name = Validator::sanitize($_POST['name'] ?? '');
$description = Validator::sanitize($_POST['description'] ?? '');

if (empty($name)) {
    echo json_encode(['success' => false, 'message' => 'Naziv je obavezan.']);
    exit;
}

$user = Auth::getCurrentUser();
$client_id = Client::create($name, $description, $user->id);

echo json_encode(['success' => true, 'message' => 'Board je uspešno kreiran!', 'client_id' => $client_id]);
?>