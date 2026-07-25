<?php
require_once "TodoRepository.php";
require_once "db.php";

class TodoController {
    public function __construct(
        private TodoRepository $repo,
        private Flash $flash,
    ) {}

    public function handle(): void {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $this->handlePost();
        } else {
            $this->showList();
        }
    }

    public function handlePost(): void {
        $action = $_POST["action"] ?? "";
        $id = (int)($_POST["id"] ?? 0);
        $title = trim($_POST["title"] ?? "");
        if ($action === "add") {
            if ($title === "") {
                $this->flash->set("error", "Title cannot be empty");
            } else {
                $this->flash->set("success", "Added: $title");
                $this->repo->create($title);
            }
        } elseif ($action === "toggle") {
            $this->repo->toggle($id);
            $this->flash->set("success", "Todo id $id updated");
        } elseif ($action === "delete") {
            $this->repo->delete($id);
            $this->flash->set("success", "Todo id $id deleted");
        }
        header("Location: todo_MVC.php");
        exit;
    }

    public function showList(): void {
        $todos = $this->repo->findAll();
        $total = $todos->count();
        $done = $todos->countDone();
        $pending = $todos->countPending();

        $successMessage = $this->flash->get("success");
        $errorMessage = $this->flash->get("error");

        require "views/todo.view.php";
    }
}