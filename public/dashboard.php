<?php
require_once __DIR__ . '/../src/config/config.php';
require_once __DIR__ . '/../src/classes/Auth.php';
require_once __DIR__ . '/../src/classes/Client.php';

Auth::requireLogin();
$user = Auth::getCurrentUser();

// Učitaj klijente za ovog korisnika
if ($user->role === 'super_admin' || $user->role === 'account_manager') {
    $clients = Client::getAll();
} else {
    $clients = Client::getByUser($user->id);
}
?>
<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Client Portal</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<div class="container">
    <?php include __DIR__ . '/../src/views/layout/navbar.php'; ?>

    <main class="main-content">
        <div class="header">
            <h1>Pregled Boardova</h1>
            <?php if (Auth::hasPermission('create_client')): ?>
                <a href="/board_create.php" class="btn btn-primary">+ Novi Board</a>
            <?php endif; ?>
        </div>

        <div class="clients-grid">
            <?php foreach ($clients as $client): ?>
                <div class="client-card">
                    <h3><?php echo htmlspecialchars($client->name); ?></h3>
                    <p><?php echo htmlspecialchars($client->description); ?></p>
                    <div class="card-footer">
                        <a href="/board.php?id=<?php echo $client->id; ?>" class="btn btn-sm">Otvori</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
</div>
</body>
</html>