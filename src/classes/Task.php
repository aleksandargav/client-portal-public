<?php
require_once __DIR__ . '/../config/Database.php';

class Task {
    public static function create($client_id, $title, $description, $created_by, $assigned_to = null, $priority = 'medium', $due_date = null) {
        $db = Database::getInstance();
        $stmt = $db->prepare('
            INSERT INTO tasks (client_id, title, description, created_by, assigned_to, status, priority, due_date)
            VALUES (?, ?, ?, ?, ?, "todo", ?, ?)
        ');
        $stmt->execute([$client_id, $title, $description, $created_by, $assigned_to, $priority, $due_date]);
        return $db->lastInsertId();
    }

    public static function getById($id) {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT * FROM tasks WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public static function getByClient($client_id) {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT * FROM tasks WHERE client_id = ? ORDER BY created_at DESC');
        $stmt->execute([$client_id]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function getByStatus($client_id, $status) {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT * FROM tasks WHERE client_id = ? AND status = ? ORDER BY created_at DESC');
        $stmt->execute([$client_id, $status]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function updateStatus($id, $status) {
        $db = Database::getInstance();
        $stmt = $db->prepare('UPDATE tasks SET status = ?, updated_at = NOW() WHERE id = ?');
        return $stmt->execute([$status, $id]);
    }

    public static function update($id, $title, $description, $priority = null, $due_date = null) {
        $db = Database::getInstance();
        $stmt = $db->prepare('UPDATE tasks SET title = ?, description = ?, priority = ?, due_date = ?, updated_at = NOW() WHERE id = ?');
        return $stmt->execute([$title, $description, $priority, $due_date, $id]);
    }

    public static function assign($id, $user_id) {
        $db = Database::getInstance();
        $stmt = $db->prepare('UPDATE tasks SET assigned_to = ? WHERE id = ?');
        return $stmt->execute([$user_id, $id]);
    }

    public static function delete($id) {
        $db = Database::getInstance();
        $stmt = $db->prepare('DELETE FROM tasks WHERE id = ?');
        return $stmt->execute([$id]);
    }

    public static function getByAssignee($user_id) {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT * FROM tasks WHERE assigned_to = ? ORDER BY due_date ASC, created_at DESC');
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
?>