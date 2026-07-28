<?php
class TodoRepository implements TodoRepositoryInterface
{
    public function __construct(
        private PDO $pdo
    ) {}

    public function findAll(): TodoList
    {
        $stmt = $this->pdo->prepare("SELECT id, title, is_done, created_at FROM todos");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        $list = new TodoList();
        foreach ($rows as $row) {
            $list->add($this->hydrate($row));
        }
        return $list;
    }

    public function findById(int $id): ?Todo
    {
        $stmt = $this->pdo->prepare("SELECT id, title, is_done, created_at FROM todos WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if ($row === false) {
            return null;
        }
        return $this->hydrate($row);
    }

    public function save(Todo $todo): void
    {
        if ($todo->getId() === null) {
            $stmt = $this->pdo->prepare("INSERT INTO todos (title) VALUES (?)");
            $stmt->execute([$todo->getTitle()]);
            $newId = $this->pdo->lastInsertId();
            $todo->setId((int) $newId);
            return;
        }
        $stmt = $this->pdo->prepare("UPDATE todos SET title = ?, is_done = ? WHERE id = ?");
        $stmt->execute([
            $todo->getTitle(),
            (int) $todo->isDone(),
            $todo->getId(),
        ]); 
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM todos WHERE id = ?");
        $stmt->execute([$id]);
    }

    private function hydrate(array $row): Todo
    {
        return new Todo(
            (int) $row["id"],
            $row["title"],
            (bool) $row["is_done"],
            $row["created_at"],
        );
    }
}