<?php
use App\Database\Database;

require '../vendor/autoload.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pageTitle = "Page de déconnexion";

$auth = Database::getAuth();

if ($auth) {
    $auth->logout();
} else {
    header('Location: /500');
}

?>