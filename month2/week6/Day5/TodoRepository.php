<?php
require_once "Todo.php";
require_once "TodoList.php";

class TodoRepository {
    private PDO $pdo;
    
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function findAll(): TodoList {
        $stmt = $this->pdo->prepare("SELECT id, title, is_done, created_at FROM todos");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        $list = new TodoList();
        foreach ($rows as $row) {
            $list->add(new Todo(
                $row["id"],
                $row["title"],
                (bool)$row["is_done"],
                $row["created_at"]
            ));
        }
        return $list;
    }

    public function findById(int $id): ?Todo {
        $stmt = $this->pdo->prepare("SELECT id, title, is_done, created_at FROM todos WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if (!$row) {return null; }
        return new Todo($row["id"], $row["title"], (bool)$row["is_done"], $row["created_at"]);
    }
    public function create(string $title): Todo {
        $stmt = $this->pdo->prepare("INSERT INTO todos (title) VALUES (?)");
        $stmt->execute([$title]);
        $newId = $this->pdo->lastInsertId();
        return $this->findById($newId);
    }

    public function toggle(int $id): void {
        $stmt = $this->pdo->prepare("UPDATE todos SET is_done = NOT is_done WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function delete(int $id): void {
        $stmt = $this->pdo->prepare("DELETE FROM todos WHERE id = ?");
        $stmt->execute([$id]);
    }
}