<?php 

namespace App;

use AltoRouter;

class Router {

    private $viewPath;
    private $router;

    public function __construct(string $viewPath)
    {
        $this->viewPath = $viewPath;
        $this->router = new AltoRouter();
    }

    public function get(string $url, string $view, ?string $name = null): self
    {
        $this->router->map('GET', $url, $view, $name);
        return $this;
    }

    public function post(string $url, string $view): self
    {
        $this->router->map('POST', $url, $view);
        return $this;
    }


    public function run(): self
    {
        
        $match = $this->router->match();
        $view =  $match['target'] ?? null;
        $params = $match['params'] ?? null;

        // If no match found, show 404 error page
        if ($match === false) {
            ob_start();
            require "../templates/errors/404.php";
            $pageContent = ob_get_clean();
            require '../elements/layout.php';
        } elseif (is_array($match)) {
            // If match is callable, call it with params
            if (is_callable($view)) {
                call_user_func_array($view, $params);
            } else {
                // Else, require the matched template
                ob_start();
                require $this->viewPath . DIRECTORY_SEPARATOR . $view . '.php';
                $pageContent = ob_get_clean();
            }
            require '../elements/layout.php';
        }
        return $this;
    }

    
}