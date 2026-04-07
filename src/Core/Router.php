<?php
namespace App\Core;

class Router{
    private array $routes = [];

    public function addRoute($route, $handler){
        $this->routes[$route] = $handler;
    }

    public function run() {
        $url = $_SERVER['REQUEST_URI'];
        if (isset($this->routes[$url])) {
            $handler = $this->routes[$url];
            $handler();
        } else {echo "404";}
    }
}