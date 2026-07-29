<?php
interface TodoRepositoryInterface
{
    public function findAll(): TodoList;
    public function findById(int $id): ?Todo;
    public function save(Todo $todo): void;
    public function delete(int $id): void;
}