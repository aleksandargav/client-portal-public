<?php
// Load environment variables
$env_path = __DIR__ . '/.env';
if (file_exists($env_path)) {
    $env = parse_ini_file($env_path);
} else {
    $env = [];
}

const SITE_URL = 'http://localhost:8000';
const SITE_NAME = 'Client Portal';
const UPLOADS_DIR = __DIR__ . '/../../uploads';

// Session configuration
ini_set('session.name', 'cp_session');
ini_set('session.gc_maxlifetime', 3600);
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 0); // Set to 1 if HTTPS
ini_set('session.use_only_cookies', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Error handling
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../../logs/error.log');

// Create logs directory if not exists
$logs_dir = __DIR__ . '/../../logs';
if (!is_dir($logs_dir)) {
    mkdir($logs_dir, 0755, true);
}
?>