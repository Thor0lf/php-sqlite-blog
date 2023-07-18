<?php 
use App\Database\Database;
use App\Routes\Router;

require '../vendor/autoload.php';

$auth = Database::getAuth();
$user = $auth->user();
$auth->sessionTimeout();

$uri = $_SERVER['REQUEST_URI'];

$router = new Router(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'templates');
require '../config/routes.php';

// Match current URI to routes
$match = $router->run();

?>