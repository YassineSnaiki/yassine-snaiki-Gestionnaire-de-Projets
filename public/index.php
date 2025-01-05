<?php
require_once __DIR__.'/../vendor/autoload.php';

use app\controllers\SiteController;
use app\controllers\AuthController;
use app\controllers\ProjectController;
use app\core\Application;

session_start();

$app = new Application(dirname(__DIR__));

// Auth routes
$app->router->get('/login', [AuthController::class, 'login']);
$app->router->post('/login', [AuthController::class, 'handleLogin']);


$app->router->get('/register', [AuthController::class, 'register']);
$app->router->post('/register', [AuthController::class, 'handleRegister']);


$app->router->get('/logout', [AuthController::class, 'logout']);

// Project routes
$app->router->get('/', [ProjectController::class, 'projects']);
$app->router->post('/', [ProjectController::class, 'handleProjects']);
// $app->router->post('/projects', [ProjectController::class, 'handleProjects']);

// $app->router->get('/projects', [ProjectController::class, 'projects']);
$app->router->post('/delete-project', [ProjectController::class, 'deleteProject']);
$app->router->post('/add-contribution', [ProjectController::class, 'addContribution']);
$app->router->post('/delete-contribution', [ProjectController::class, 'deleteContribution']);




$app->router->get('/kanban', [ProjectController::class, 'kanban']);


$app->router->post('/add-task', [ProjectController::class, 'addTask']);
$app->router->post('/update-task-status', [ProjectController::class, 'updateTaskStatus']);
$app->router->post('/change-task-tag', [ProjectController::class, 'changeTaskTag']);
$app->router->post('/delete-task', [ProjectController::class, 'deleteTask']);
$app->router->post('/assign-task', [ProjectController::class, 'assignTask']);
$app->router->post('/unassign-task', [ProjectController::class, 'unassignTask']);



// Other routes
$app->router->get('/contact', [SiteController::class, 'contact']);
$app->router->post('/contact', [SiteController::class, 'handleContact']);

$app->run();