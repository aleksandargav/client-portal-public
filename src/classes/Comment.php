<?php
require_once __DIR__ . '/../config/Database.php';

class Comment {
    public static function create($task_id, $user_id, $comment) {
        $db = Database::getInstance();
        $stmt = $db->prepare('INSERT INTO task_comments (task_id, user_id, comment) VALUES (?, ?, ?)');
        return $stmt->execute([$task_id, $user_id, $comment]);
    }

    public static function getByTask($task_id) {
        $db = Database::getInstance();
        $stmt = $db->prepare('
            SELECT tc.*, u.name, u.avatar FROM task_comments tc
            INNER JOIN users u ON tc.user_id = u.id
            WHERE tc.task_id = ?
            ORDER BY tc.created_at DESC
        ');
        $stmt->execute([$task_id]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function delete($id) {
        $db = Database::getInstance();
        $stmt = $db->prepare('DELETE FROM task_comments WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
?>