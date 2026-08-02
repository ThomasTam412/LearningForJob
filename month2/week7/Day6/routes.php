<?php

use App\Controllers\NoteController;

return [
    "GET /notes" => [NoteController::class, "index"],
    "POST /notes" => [NoteController::class, "store"],
    "POST /notes/delete" => [NoteController::class, "delete"],
    "POST /notes/toggle" => [NoteController::class, "toggle"],
];