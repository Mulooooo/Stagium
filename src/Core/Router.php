<?php
namespace App\Core;

class Router{
    private array $routes = [];

    public function addRoute($route, $handler, $roles = []){
        $this->routes[$route] = [
            'handler' => $handler,
            'roles' => $roles
        ];
    }

    public function run() {
        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        if (isset($this->routes[$url])) {
            $route = $this->routes[$url];
            $handler = $route['handler'];
            $roles = $route['roles'];
            if (!empty($roles)) {
                if (!isset($_SESSION['user_id'])) {
                    header('Location: /login');
                    exit;
                }
                if (!in_array($_SESSION['user_role'], $roles)) {
                    http_response_code(403);
                    die("403 Forbidden");
                }
            }
            $controller = new $handler[0]();
            $controller->{$handler[1]}();
        } else {
            http_response_code(404);
            die("404 Not Found");
        }
    }
}