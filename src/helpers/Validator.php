<?php
class Validator {
    public static function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function validatePassword($password) {
        // Minimalno 8 karaktera, barem jedno malo slovo, jedno isto slovo i jedan broj
        return strlen($password) >= 8 &&
            preg_match('/[a-z]/', $password) &&
            preg_match('/[A-Z]/', $password) &&
            preg_match('/[0-9]/', $password);
    }

    public static function sanitize($input) {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }

    public static function validateRequired($data, $required_fields) {
        $missing = [];
        foreach ($required_fields as $field) {
            if (empty($data[$field])) {
                $missing[] = $field;
            }
        }
        return $missing;
    }
}
?>