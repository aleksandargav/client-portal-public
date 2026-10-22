<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../src/config/config.php';
require_once __DIR__ . '/../src/classes/Auth.php';
require_once __DIR__ . '/../src/classes/Task.php';
require_once __DIR__ . '/../src/classes/Logger.php';
require_once __DIR__ . '/../src/helpers/Security.php';
require_once __DIR__ . '/../src/helpers/Validator.php';

Auth::requireLogin();
Auth::requirePermission('create_task');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$csrf_token = $_POST['csrf_token'] ?? '';
if (!Security::validateCSRFToken($csrf_token)) {
    echo json_encode(['success' => false, 'message' => 'CSRF validation failed']);
    exit;
}

$client_id = (int)($_POST['client_id'] ?? 0);
$title = Validator::sanitize($_POST['title'] ?? '');
$description = Validator::sanitize($_POST['description'] ?? '');
$assigned_to = !empty($_POST['assigned_to']) ? (int)$_POST['assigned_to'] : null;
$priority = $_POST['priority'] ?? 'medium';
$due_date = !empty($_POST['due_date']) ? $_POST['due_date'] : null;

if (empty($title) || $client_id === 0) {
    echo json_encode(['success' => false, 'message' => 'Obavezna polja nisu popunjena.']);
    exit;
}

$user = Auth::getCurrentUser();
$task_id = Task::create($client_id, $title, $description, $user->id, $assigned_to, $priority, $due_date);

Logger::log($user->id, $client_id, $task_id, 'task_created');

echo json_encode(['success' => true, 'message' => 'Zadatak je uspešno kreiran!', 'task_id' => $task_id]);
?>