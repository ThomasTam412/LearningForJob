<?php

require __DIR__ . "/../autoload.php";

use App\Repositories\PdoTodoRepository;
$pdo = new PDO(
    "mysql:host=localhost;dbname=learning_db;charset=utf8mb4",
    "root",
    "",
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]
);
$repo = new PdoTodoRepository($pdo);
// use App\Repositories\JsonTodoRepository;

// $repo = new JsonTodoRepository(__DIR__ . "/../todos.json");
$routes = require __DIR__ . "/../routes.php";

$method = $_SERVER["REQUEST_METHOD"];
$uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$key = $method . " " . $uri;

if (!isset($routes[$key])) {
    http_response_code(404);
    echo "404 Not Found";
    return;
}

[$class, $action] = $routes[$key];
$controller = new $class($repo);
$controller->$action();