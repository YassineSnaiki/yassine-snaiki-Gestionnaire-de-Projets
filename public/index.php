<?php
require_once __DIR__.'/../vendor/autoload.php';


use app\controllers\AuthController;
use app\controllers\ContributionController;
use app\controllers\PermissionController;
use app\controllers\ProjectController;
use app\controllers\RoleController;
use app\controllers\TaskController;
use app\controllers\AdminController;
use app\core\Application;


session_start();


$app = new Application(dirname(__DIR__));

// Auth routes
$app->router->get('/login', [AuthController::class, 'login']);
$app->router->post('/login', [AuthController::class, 'handleLogin']);


$app->router->get('/register', [AuthController::class, 'register']);
$app->router->post('/register', [AuthController::class, 'handleRegister']);


$app->router->get('/logout', [AuthController::class, 'logout']);

// Admin routes
$app->router->get('/admin-dashboard', [AdminController::class, 'adminDashboard']);

// Project routes
$app->router->get('/', [ProjectController::class, 'projects']);
$app->router->post('/add-project', [ProjectController::class, 'addProject']);



$app->router->post('/delete-project', [ProjectController::class, 'deleteProject']);
$app->router->post('/add-contribution', [ContributionController::class, 'addContribution']);
$app->router->post('/delete-contribution', [ContributionController::class, 'deleteContribution']);




$app->router->get('/kanban', [ProjectController::class, 'kanban']);


$app->router->post('/add-task', [TaskController::class, 'addTask']);
$app->router->post('/update-task-status', [TaskController::class, 'updateTaskStatus']);
$app->router->post('/change-task-tag', [TaskController::class, 'changeTaskTag']);
$app->router->post('/delete-task', [TaskController::class, 'deleteTask']);
$app->router->post('/assign-task', [TaskController::class, 'assignTask']);
$app->router->post('/unassign-task', [TaskController::class, 'unassignTask']);


$app->router->post('/change-role', [RoleController::class, 'changeRole']);



$app->router->post('/create-permission', [PermissionController::class, 'createPermission']);
$app->router->post('/delete-permission', [PermissionController::class, 'deletePermission']);
$app->router->post('/add-role', [RoleController::class, 'addRole']);
$app->router->post('/delete-role', [RoleController::class, 'deleteRole']);
$app->router->post('/add-permission', [RoleController::class, 'addPermission']);
$app->router->post('/remove-permission', [RoleController::class, 'removePermission']);







$app->run();