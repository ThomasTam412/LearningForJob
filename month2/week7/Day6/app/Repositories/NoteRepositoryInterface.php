<?php
namespace App\Repositories;

use App\Models\Note;
use App\Models\NoteList;

interface NoteRepositoryInterface
{
    public function findAll(): NoteList;
    public function findById(int $id): ?Note;
    public function save(Note $note): void;
    public function delete(int $id): void;
}