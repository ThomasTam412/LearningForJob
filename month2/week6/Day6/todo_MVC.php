<?php
require_once "db.php";
require_once "TodoController.php";

$controller = new TodoController(
    new TodoRepository($pdo),
    new Flash(),
);

$controller->handle();