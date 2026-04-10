<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';
use App\Core\Router;
use App\Controllers\HomeController;

$db = App\Models\Database::getInstance();

$r = new Router();

$r->addRoute('/', function() {
    $c = new HomeController();
    $c->index();
});
$r->addRoute('/about', function() { echo "About page";});
$r->addRoute('/contact', function() { echo "Contact page";});

$r->run();