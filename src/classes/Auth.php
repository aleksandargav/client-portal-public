<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/Permission.php';

class Auth {

    public static function isLoggedIn() {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }

    public static function requireLogin() {
        if (!self::isLoggedIn()) {
            header('Location: /login.php');
            exit;
        }
    }

    public static function getCurrentUser() {
        if (!self::isLoggedIn()) {
            return null;
        }

        $db = Database::getInstance();
        $stmt = $db->prepare('SELECT id, name, email, role, avatar, is_active FROM users WHERE id = ? AND is_active = 1');
        $stmt->execute([$_SESSION['user_id']]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public static function getCurrentUserId() {
        return $_SESSION['user_id'] ?? null;
    }

    public static function hasPermission($action) {
        $user = self::getCurrentUser();
        if (!$user) {
            return false;
        }
        return Permission::can($user->role, $action);
    }

    public static function requirePermission($action) {
        if (!self::hasPermission($action)) {
            http_response_code(403);
            die('Nemate dozvolu za ovu akciju.');
        }
    }

    public static function login($email, $password) {
        $db = Database::getInstance();

        $stmt = $db->prepare('SELECT id, name, email, password_hash, role, is_active FROM users WHERE email = ? AND is_active = 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_OBJ);

        if (!$user) {
            return ['success' => false, 'message' => 'Korisnik sa ovim email-om nije pronađen.'];
        }

        if (!password_verify($password, $user->password_hash)) {
            return ['success' => false, 'message' => 'Pogrešna lozinka.'];
        }

        // Regenerate session ID (security)
        session_regenerate_id(true);

        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_role'] = $user->role;

        // Update last login
        $update_stmt = $db->prepare('UPDATE users SET last_login = NOW() WHERE id = ?');
        $update_stmt->execute([$user->id]);

        return ['success' => true, 'message' => 'Uspešno ste se ulogovali!'];
    }

    public static function logout() {
        session_destroy();
        setcookie(session_name(), '', time() - 3600, '/');
        header('Location: /login.php');
        exit;
    }

    public static function register($name, $email, $password, $role = 'client') {
        $db = Database::getInstance();

        // Check if email exists
        $stmt = $db->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            return ['success' => false, 'message' => 'Email već postoji.'];
        }

        // Validate role
        $allowed_roles = ['super_admin', 'account_manager', 'specialist', 'client'];
        if (!in_array($role, $allowed_roles)) {
            return ['success' => false, 'message' => 'Nevažeća uloga.'];
        }

        // Hash password with Argon2id
        $password_hash = password_hash($password, PASSWORD_ARGON2ID);

        try {
            $stmt = $db->prepare('INSERT INTO users (name, email, password_hash, role, is_active) VALUES (?, ?, ?, ?, 1)');
            $stmt->execute([$name, $email, $password_hash, $role]);
            return ['success' => true, 'message' => 'Korisnik je uspešno kreiran!'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Greška pri kreiranju korisnika.'];
        }
    }
}
?>