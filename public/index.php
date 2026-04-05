<?php

$routes = [
    '/' => function() { echo "Home page"; },
    '/about' => function() { echo "About page"; },
    '/contact' => function() { echo "Contact page"; },
];

$url = $_SERVER['REQUEST_URI'];

if (isset($routes[$url])) {
    $handler = $routes[$url];
    $handler();
} else {echo "404";}