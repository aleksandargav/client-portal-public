<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../src/config/config.php';
require_once __DIR__ . '/../src/classes/Auth.php';
require_once __DIR__ . '/../src/classes/Comment.php';
require_once __DIR__ . '/../src/classes/Logger.php';
require_once __DIR__ . '/../src/helpers/Security.php';
require_once __DIR__ . '/../src/helpers/Validator.php';

Auth::requireLogin();
Auth::requirePermission('comment_on_task');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$csrf_token = $_POST['csrf_token'] ?? '';
if (!Security::validateCSRFToken($csrf_token)) {
    echo json_encode(['success' => false, 'message' => 'CSRF validation failed']);
    exit;
}

$task_id = (int)($_POST['task_id'] ?? 0);
$comment_text = Validator::sanitize($_POST['comment'] ?? '');

if ($task_id === 0 || empty($comment_text)) {
    echo json_encode(['success' => false, 'message' => 'Obavezna polja nisu popunjena.']);
    exit;
}

$user = Auth::getCurrentUser();
Comment::create($task_id, $user->id, $comment_text);

echo json_encode(['success' => true, 'message' => 'Komentar je dodat!']);
?>