<?php

require_once __DIR__ . '/../src/Core/Router.php';
require_once __DIR__ . '/../src/Controllers/HomeController.php';

$r = new Router();

$r->addRoute('/', function() {
    $c = new HomeController();
    $c->index();
});
$r->addRoute('/about', function() { echo "About page";});
$r->addRoute('/contact', function() { echo "Contact page";});

$r->run();