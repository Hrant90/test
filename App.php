<?php

use Api\Route;
use Controllers\TasksController;
use Repositories\TaskRepository;
use Repositories\TaskRepositoryInterface;
use Services\Container;
use Services\Database;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$container = new Container();
$container->bind(TaskRepositoryInterface::class, TaskRepository::class);
$container->setParameters(Database::class, [
    'host'     => $_ENV['DB_HOST'] ?? 'localhost',
    'dbname'   => $_ENV['DB_NAME'] ?? 'mvc_db',
    'username' => $_ENV['DB_USER'] ?? 'root',
    'password' => $_ENV['DB_PASS'] ?? ''
]);
$route = new Route($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
$route->get('/mvc-api/api/v1/task', [TasksController::class, 'getAllTasks']);
$route->get('/mvc-api/api/v1/task/{task_id}', [TasksController::class, 'getTaskById']);
$route->dispatch();
