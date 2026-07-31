<?php

use App\Controllers\TodoController;

return [
    "GET /todos" => [TodoController::class, "index"],
    "POST /todos" => [TodoController::class, "store"],
];