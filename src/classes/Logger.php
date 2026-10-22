<?php
require_once __DIR__ . '/../config/Database.php';

class Logger {
    public static function log($user_id, $client_id, $task_id, $action, $changes = null) {
        $db = Database::getInstance();
        $changes_json = $changes ? json_encode($changes) : null;
        $stmt = $db->prepare('
            INSERT INTO activity_log (user_id, client_id, task_id, action, changes)
            VALUES (?, ?, ?, ?, ?)
        ');
        return $stmt->execute([$user_id, $client_id, $task_id, $action, $changes_json]);
    }

    public static function getByClient($client_id, $limit = 50) {
        $db = Database::getInstance();
        $stmt = $db->prepare('
            SELECT al.*, u.name FROM activity_log al
            INNER JOIN users u ON al.user_id = u.id
            WHERE al.client_id = ?
            ORDER BY al.created_at DESC
            LIMIT ?
        ');
        $stmt->bindParam(1, $client_id, PDO::PARAM_INT);
        $stmt->bindParam(2, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
?>