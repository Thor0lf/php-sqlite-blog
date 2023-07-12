<?php 
use App\Database;
require '../vendor/autoload.php';

$auth = Database::getAuth();
$user = $auth->user();
$auth->sessionTimeout();

$uri = $_SERVER['REQUEST_URI'];
 
$router = new AltoRouter();
require '../config/routes.php';

// Match current URI to routes
$match = $router->match();

// If no match found, show 404 error page
if ($match === false) {
    ob_start();
    require "../templates/errors/404.php";
    $pageContent = ob_get_clean();
    require '../elements/layout.php';
} elseif (is_array($match)) {
    // If match is callable, call it with params
    if (is_callable($match['target'])) {
        call_user_func_array($match['target'], $match['params']);
    } else {
        // Else, require the matched template
        $params = $match['params'];
        ob_start();
        require "../templates/{$match['target']}.php";
        $pageContent = ob_get_clean();
    }
    require '../elements/layout.php';
}

?>