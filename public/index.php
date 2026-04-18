<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';
use App\Core\Router;
use App\Controllers\HomeController;
use App\Controllers\OfferController;
use App\Controllers\AuthController;
use App\Controllers\CompanyController;

session_start();

$db = App\Models\Database::getInstance();

$r = new Router();

$r->addRoute('/', [HomeController::class, 'index']);

$r->addRoute('/login', [AuthController::class, 'login']);
$r->addRoute('/logout', [AuthController::class, 'logout']);

$r->addRoute('/offers', [OfferController::class, 'index']);
$r->addRoute('/offers/show', [OfferController::class, 'show']);

$r->addRoute('/companies', [CompanyController::class, 'index']);
$r->addRoute('/companies/show', [CompanyController::class, 'show']);
$r->addRoute('/companies/create', [CompanyController::class, 'create'], ['pilote', 'administrateur']);
$r->addRoute('/companies/delete', [CompanyController::class, 'delete'], ['pilote', 'administrateur']);

$r->run();