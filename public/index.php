<?php
require_once __DIR__ . '/../src/config/config.php';
require_once __DIR__ . '/../src/classes/Auth.php';

// Ako je korisnik logovan, prebacimo ga na dashboard
if (Auth::isLoggedIn()) {
    header('Location: /dashboard.php');
    exit;
}

// Ako nije, prikaži login formu
header('Location: /login.php');
exit;
?>