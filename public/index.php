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
use App\Controllers\WishlistController;
use App\Controllers\LegalController;
use App\Controllers\EvaluationController;
use App\Controllers\ProfileController;
use App\Controllers\PromotionController;

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
$r->addRoute('/companies/evaluate', [EvaluationController::class, 'evaluate'], ['pilote', 'administrateur']);
$r->addRoute('/companies/sites/create', [CompanyController::class, 'createSite'], ['pilote', 'administrateur']);

$r->addRoute('/student/applications', [ApplicationController::class, 'myApplications'], ['etudiant']);

$r->addRoute('/students', [StudentController::class, 'index'], ['pilote', 'administrateur']);
$r->addRoute('/students/show', [StudentController::class, 'show'], ['pilote', 'administrateur']);
$r->addRoute('/students/create', [StudentController::class, 'create'], ['pilote', 'administrateur']);
$r->addRoute('/students/edit', [StudentController::class, 'edit'], ['pilote', 'administrateur']);
$r->addRoute('/students/delete', [StudentController::class, 'delete'], ['pilote', 'administrateur']);

$r->addRoute('/pilots', [PilotController::class, 'index'], ['administrateur']);
$r->addRoute('/pilots/show', [PilotController::class, 'show'], ['administrateur']);
$r->addRoute('/pilots/create', [PilotController::class, 'create'], ['administrateur']);
$r->addRoute('/pilots/edit', [PilotController::class, 'edit'], ['administrateur']);
$r->addRoute('/pilots/delete', [PilotController::class, 'delete'], ['administrateur']);

$r->addRoute('/wishlist', [WishlistController::class, 'index'], ['etudiant']);
$r->addRoute('/wishlist/toggle', [WishlistController::class, 'toggle'], ['etudiant']);

$r->addRoute('/pilot/applications', [ApplicationController::class, 'pilotApplications'], ['pilote']);

$r->addRoute('/mentions-legales', [LegalController::class, 'index']);

$r->addRoute('/profile', [ProfileController::class, 'index'], ['etudiant', 'pilote', 'administrateur']);
$r->addRoute('/profile/update', [ProfileController::class, 'update'], ['etudiant', 'pilote', 'administrateur']);

$r->addRoute('/promotions', [PromotionController::class, 'index'], ['pilote', 'administrateur']);
$r->addRoute('/promotions/show', [PromotionController::class, 'show'], ['pilote', 'administrateur']);
$r->addRoute('/promotions/create', [PromotionController::class, 'create'], ['administrateur']);
$r->addRoute('/promotions/addStudent', [PromotionController::class, 'addStudent'], ['pilote', 'administrateur']);
$r->addRoute('/promotions/addPilot', [PromotionController::class, 'addPilot'], ['administrateur']);
$r->addRoute('/promotions/removeStudent', [PromotionController::class, 'removeStudent'], ['pilote', 'administrateur']);
$r->addRoute('/promotions/removePilot', [PromotionController::class, 'removePilot'], ['administrateur']);
$r->addRoute('/promotions/delete', [PromotionController::class, 'delete'], ['administrateur']);

$r->run();