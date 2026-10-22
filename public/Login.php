<?php
require_once __DIR__ . '/../src/config/config.php';
require_once __DIR__ . '/../src/classes/Auth.php';
require_once __DIR__ . '/../src/helpers/Security.php';
require_once __DIR__ . '/../src/helpers/Validator.php';

$error = '';
$success = '';

// Ako je korisnik već logovan
if (Auth::isLoggedIn()) {
    header('Location: /dashboard.php');
    exit;
}

// Obrada login forme
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf_token = $_POST['csrf_token'] ?? '';

    if (!Security::validateCSRFToken($csrf_token)) {
        $error = 'Nevažeći CSRF token!';
    } else {
        $email = Validator::sanitize($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $error = 'Molimo unesite email i lozinku.';
        } else {
            $result = Auth::login($email, $password);
            if ($result['success']) {
                header('Location: /dashboard.php');
                exit;
            } else {
                $error = $result['message'];
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Prijava - Client Portal</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body class="login-page">
<div class="login-container">
    <div class="login-box">
        <h1>Client Portal</h1>
        <p>Client Portal</p>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="/login.php">
            <?php echo Security::getCSRFField(); ?>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required autofocus>
            </div>

            <div class="form-group">
                <label for="password">Lozinka:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="btn btn-primary">Prijavi se</button>
        </form>

        <div class="login-footer">
            <p>© 2026 Client Portal</p>
        </div>
    </div>
</div>
</body>
</html>