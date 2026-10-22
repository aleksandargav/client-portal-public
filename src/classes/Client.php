<?php
require_once __DIR__ . '/../config/Database.php';

class Client {
    public static function create($name, $description, $created_by) {
        $db = Database::getInstance();
        $stmt = $db->prepare('INSERT INTO clients (name, description, created_by, status) VALUES (?, ?, ?, "active")');
        $stmt->execute([$name, $description, $created_by]);
        return $db->lastInsertId();
    }

    public static function getById($id) {
        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT * FROM clients WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public static function getAll() {
        $db = Database::getInstance();
        $stmt = $db->query('SELECT * FROM clients WHERE status = "active" ORDER BY created_at DESC');
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function getByUser($user_id) {
        $db = Database::getInstance();
        $stmt = $db->prepare('
            SELECT DISTINCT c.* FROM clients c
            LEFT JOIN client_team_members ctm ON c.id = ctm.client_id
            WHERE c.created_by = ? OR ctm.user_id = ?
            ORDER BY c.created_at DESC
        ');
        $stmt->execute([$user_id, $user_id]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function update($id, $name, $description) {
        $db = Database::getInstance();
        $stmt = $db->prepare('UPDATE clients SET name = ?, description = ? WHERE id = ?');
        return $stmt->execute([$name, $description, $id]);
    }

    public static function delete($id) {
        $db = Database::getInstance();
        $stmt = $db->prepare('UPDATE clients SET status = "archived" WHERE id = ?');
        return $stmt->execute([$id]);
    }

    public static function addTeamMember($client_id, $user_id, $added_by) {
        $db = Database::getInstance();
        $stmt = $db->prepare('INSERT INTO client_team_members (client_id, user_id, added_by) VALUES (?, ?, ?)');
        return $stmt->execute([$client_id, $user_id, $added_by]);
    }

    public static function getTeamMembers($client_id) {
        $db = Database::getInstance();
        $stmt = $db->prepare('
            SELECT u.id, u.name, u.email, u.role FROM users u
            INNER JOIN client_team_members ctm ON u.id = ctm.user_id
            WHERE ctm.client_id = ?
            ORDER BY u.name
        ');
        $stmt->execute([$client_id]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
?>