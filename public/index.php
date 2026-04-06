<?php

require_once __DIR__ . '/../src/Core/Router.php';

$r = new Router();

$r->addRoute('/', function() { echo "Home page";});
$r->addRoute('/about', function() { echo "About page";});
$r->addRoute('/contact', function() { echo "Contact page";});

$r->run();