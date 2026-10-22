<?php
require_once __DIR__ . '/../config/Database.php';

class User {
    public static function getById($id) {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT id, name, email, role, avatar, is_active, created_at FROM users WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public static function getAll() {
        $db = Database::getInstance();
        $stmt = $db->query('SELECT id, name, email, role, is_active, created_at FROM users ORDER BY created_at DESC');
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function getAllByRole($role) {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT id, name, email FROM users WHERE role = ? AND is_active = 1 ORDER BY name');
        $stmt->execute([$role]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function update($id, $name, $email) {
        $db = Database::getInstance();
        $stmt = $db->prepare('UPDATE users SET name = ?, email = ? WHERE id = ?');
        return $stmt->execute([$name, $email, $id]);
    }

    public static function changeRole($id, $role) {
        $db = Database::getInstance();
        $stmt = $db->prepare('UPDATE users SET role = ? WHERE id = ?');
        return $stmt->execute([$role, $id]);
    }

    public static function deactivate($id) {
        $db = Database::getInstance();
        $stmt = $db->prepare('UPDATE users SET is_active = 0 WHERE id = ?');
        return $stmt->execute([$id]);
    }

    public static function activate($id) {
        $db = Database::getInstance();
        $stmt = $db->prepare('UPDATE users SET is_active = 1 WHERE id = ?');
        return $stmt->execute([$id]);
    }
}
?>