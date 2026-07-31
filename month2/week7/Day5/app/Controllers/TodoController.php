<?php

namespace App\Controllers;

use App\Models\Todo;
use App\Repositories\TodoRepositoryInterface;

class TodoController
{
    public function __construct(
        private TodoRepositoryInterface $repo,
    ) {}

    public function index(): void
    {
        $todos = $this->repo->findAll()->all();
        foreach ($todos as $todo) {
            echo "Id: " . htmlspecialchars($todo->getId()) . " ";
            echo "Title: " . htmlspecialchars($todo->getTitle()) . " ";
            echo $todo->isDone() ? "Done" : "Pending" . " ";
            echo "Created At: " . htmlspecialchars($todo->getCreatedAt()) . "<br>";
        }
        echo '<form method="POST" action="/todos">';
        echo '<input name="title">';
        echo '<button>新增</button>';
        echo '</form>';
    }

    public function store(): void
    {
        $title = trim($_POST["title"] ?? "");
        $todo = new Todo(null, $title);
        $this->repo->save($todo);
        header("Location: /todos");
        exit;
    }
}