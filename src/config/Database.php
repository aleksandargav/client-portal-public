<?php
class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        // Load .env
        $env_path = __DIR__ . '/.env';
        if (!file_exists($env_path)) {
            die('ERROR: .env fajl nije pronađen!');
        }

        $env = parse_ini_file($env_path);

        try {
            $this->pdo = new PDO(
                'mysql:host=' . $env['DB_HOST'] . ';dbname=' . $env['DB_NAME'] . ';charset=utf8mb4',
                $env['DB_USER'],
                $env['DB_PASS'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch (PDOException $e) {
            die('Database connection error: ' . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->pdo;
    }

    private function __clone() {}
    private function __wakeup() {}
}
?>