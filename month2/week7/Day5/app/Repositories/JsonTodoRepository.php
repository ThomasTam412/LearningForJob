<?php
namespace App\Repositories;

use App\Models\Todo;
use App\Models\TodoList;

class JsonTodoRepository implements TodoRepositoryInterface
{
    public function __construct(
        private string $filePath,
    ) {}

    public function findAll(): TodoList
    {
        $rows = $this->readAll();
        $todos = new TodoList();
        foreach ($rows as $row) {
            $todos->add($this->hydrate($row));
        }
        return $todos;
    }

    public function findById(int $id): ?Todo
    {
        return $this->findAll()->find($id);
    }

    public function delete(int $id): void
    {
        $rows = $this->readAll();
        foreach ($rows as $index => $row) {
            if ((int) $row["id"] === $id) {
                unset($rows[$index]);
                $rows = array_values($rows);
                $this->writeAll($rows);
                return;
            }
        }
    }

    public function save(Todo $todo): void
    {
        $rows = $this->readAll();

        if ($todo->getId() === null) {
            $ids = array_column($rows, "id");
            $newId = count($ids) ? max($ids) + 1 : 1;
            $todo->setId($newId);
            if ($todo->getCreatedAt() === null) {
                $todo->setCreatedAt(date("Y-m-d H:i:s"));
            }
            $row = $this->dehydrate($todo);
            $rows[] = $row;
            $this->writeAll($rows);
            return;
        }
        
        foreach ($rows as $index => $row) {
            if ($todo->getId() === (int) $row["id"]) {
                $newRow = $this->dehydrate($todo);
                $rows[$index] = $newRow;
                $this->writeAll($rows);
                return;
            }
        }
    }

    private function dehydrate(Todo $todo): array
    {
        return [
            "id" => $todo->getId(),
            "title" => $todo->getTitle(),
            "is_done" => $todo->isDone(),
            "created_at" => $todo->getCreatedAt(),
        ];
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

    private function readAll(): array
    {
        if (file_exists($this->filePath)) {
            $json_string = file_get_contents($this->filePath);
            return json_decode($json_string, true) ?? [];
        }
        return [];
    }

    private function writeAll(array $rows): void
    {
        $json_string = json_encode($rows, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        file_put_contents($this->filePath, $json_string);
    }
}