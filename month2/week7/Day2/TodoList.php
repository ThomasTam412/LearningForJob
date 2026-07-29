<?php
class TodoList
{
    private array $todos = [];

    public function add(Todo $todo): void
    {
        $this->todos[] = $todo;
    }

    public function find(int $id): ?Todo
    {
        foreach ($this->todos as $todo) {
            if ($todo->getId() === $id) {
                return $todo;
            }
        }
        return null;
    }

    public function remove(int $id): bool
    {
        foreach ($this->todos as $index => $todo) {
            if ($todo->getId() === $id) {
                unset($this->todos[$index]);
                $this->todos = array_values($this->todos);
                return true;
            }
        }
        return false;
    }

    public function count(): int
    {
        return count($this->todos);
    }

    public function countDone(): int
    {
        return count(array_filter($this->todos, fn($t) => $t->isDone()));
    }

    public function countPending(): int
    {
        return count(array_filter($this->todos, fn($t) => !$t->isDone()));
    }

    public function all(): array
    {
        return $this->todos;
    }
}