<?php
$user = Auth::getCurrentUser();
?>
<nav class="navbar">
    <div class="navbar-brand">
        <a href="/dashboard.php">Client Portal</a>
    </div>
    <div class="navbar-menu">
        <a href="/dashboard.php">Dashboard</a>
        <?php if (Auth::hasPermission('create_client')): ?>
            <a href="/board_create.php">Novi Board</a>
        <?php endif; ?>
    </div>
    <div class="navbar-user">
        <span><?php echo htmlspecialchars($user->name); ?> (<?php echo ucfirst($user->role); ?>)</span>
        <a href="/logout.php" class="btn btn-sm btn-danger">Odjava</a>
    </div>
</nav>