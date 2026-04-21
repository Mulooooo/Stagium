<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';
use App\Core\Router;
use App\Controllers\HomeController;
use App\Controllers\OfferController;
use App\Controllers\AuthController;
use App\Controllers\CompanyController;
use App\Controllers\ApplicationController;
use App\Controllers\StudentController;
use App\Controllers\PilotController;

session_start();

$db = App\Models\Database::getInstance();

$r = new Router();

$r->addRoute('/', [HomeController::class, 'index']);

$r->addRoute('/login', [AuthController::class, 'login']);
$r->addRoute('/logout', [AuthController::class, 'logout']);

$r->addRoute('/offers', [OfferController::class, 'index']);
$r->addRoute('/offers/show', [OfferController::class, 'show']);
$r->addRoute('/offers/create', [OfferController::class, 'create'], ['pilote', 'administrateur']);
$r->addRoute('/offers/delete', [OfferController::class, 'delete'], ['pilote', 'administrateur']);
$r->addRoute('/offers/edit', [OfferController::class, 'edit'], ['pilote', 'administrateur']);
$r->addRoute('/offers/apply', [ApplicationController::class, 'apply'], ['etudiant']);

$r->addRoute('/companies', [CompanyController::class, 'index']);
$r->addRoute('/companies/show', [CompanyController::class, 'show']);
$r->addRoute('/companies/create', [CompanyController::class, 'create'], ['pilote', 'administrateur']);
$r->addRoute('/companies/delete', [CompanyController::class, 'delete'], ['pilote', 'administrateur']);
$r->addRoute('/companies/edit', [CompanyController::class, 'edit'], ['pilote', 'administrateur']);

$r->addRoute('/student/applications', [ApplicationController::class, 'myApplications'], ['etudiant']);

$r->addRoute('/students', [StudentController::class, 'index']);
$r->addRoute('/students/show', [StudentController::class, 'show']);
$r->addRoute('/students/create', [StudentController::class, 'create'], ['pilote', 'administrateur']);
$r->addRoute('/students/edit', [StudentController::class, 'edit'], ['pilote', 'administrateur']);
$r->addRoute('/students/delete', [StudentController::class, 'delete'], ['pilote', 'administrateur']);

$r->addRoute('/pilots', [PilotController::class, 'index'], ['administrateur']);
$r->addRoute('/pilots/show', [PilotController::class, 'show'], ['administrateur']);
$r->addRoute('/pilots/create', [PilotController::class, 'create'], ['administrateur']);
$r->addRoute('/pilots/edit', [PilotController::class, 'edit'], ['administrateur']);
$r->addRoute('/pilots/delete', [PilotController::class, 'delete'], ['administrateur']);

$r->run();