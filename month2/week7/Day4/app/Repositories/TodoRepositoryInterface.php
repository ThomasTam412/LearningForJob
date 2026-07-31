<?php
namespace App\Repositories;

use App\Models\Todo;
use App\Models\TodoList;

interface TodoRepositoryInterface
{
    public function findAll(): TodoList;
    public function findById(int $id): ?Todo;
    public function save(Todo $todo): void;
    public function delete(int $id): void;
}