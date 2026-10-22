<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../src/config/config.php';
require_once __DIR__ . '/../src/classes/Auth.php';
require_once __DIR__ . '/../src/classes/Task.php';
require_once __DIR__ . '/../src/classes/Logger.php';
require_once __DIR__ . '/../src/helpers/Security.php';

Auth::requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$task_id = (int)($_POST['task_id'] ?? 0);
$new_status = $_POST['status'] ?? '';

// Samo AM i SA mogu prebaciti u DONE
if ($new_status === 'done') {
    Auth::requirePermission('move_task_to_done');
}

if ($task_id === 0 || empty($new_status)) {
    echo json_encode(['success' => false, 'message' => 'Nevažedan zahtev.']);
    exit;
}

if (!in_array($new_status, ['todo', 'in_progress', 'in_review', 'done'])) {
    echo json_encode(['success' => false, 'message' => 'Nevažedan status.']);
    exit;
}

$task = Task::getById($task_id);
if (!$task) {
    echo json_encode(['success' => false, 'message' => 'Zadatak nije pronađen.']);
    exit;
}

Task::updateStatus($task_id, $new_status);

$user = Auth::getCurrentUser();
Logger::log($user->id, $task->client_id, $task_id, 'task_moved_to_' . $new_status);

echo json_encode(['success' => true, 'message' => 'Status je ažuriran!']);
?>