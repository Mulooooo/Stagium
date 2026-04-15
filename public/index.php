<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';
use App\Core\Router;
use App\Controllers\HomeController;
use App\Controllers\OfferController;

$db = App\Models\Database::getInstance();

$r = new Router();

$r->addRoute('/', function() {
    $c = new HomeController();
    $c->index();
});

$r->addRoute('/offers', function() {
    $c = new OfferController();
    $c->index();
});

$r->addRoute('/offers/show', function() {
    $c = new OfferController();
    $c->show();
});

$r->run();