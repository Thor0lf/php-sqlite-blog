<?php
use App\Database\Database;
use App\Users\DeleteUser;

require ('../vendor/autoload.php');

$auth = Database::getAuth();
$user = $auth->user();

Database::getAuth()->requireRole('user', 'admin');

if (Database::getAuth()->user()->role === 'user') {
    $deleteUser = new DeleteUser();
    $deleteUser->processForm();
}

if (Database::getAuth()->user()->role == 'admin') {
    $deleteUser = new DeleteUser();
    $deleteUser->processFormByAdmin();
}

?>