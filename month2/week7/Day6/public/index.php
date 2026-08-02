<?php

require __DIR__ . "/../autoload.php";

use App\Repositories\JsonNoteRepository;

$repo = new JsonNoteRepository(__DIR__ . "/../notes.json");
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