<?php

namespace App\Models;

class NoteList
{
    public function __construct(
        private array $notes = [],
    ) {}

    public function add(Note $note): void
    {
        $this->notes[] = $note;
    }

    public function remove(int $id): bool
    {
        foreach ($this->notes as $index => $note) {
            if ($note->getId() === $id) {
                unset($this->notes[$index]);
                $this->notes = array_values($this->notes);
                return true;
            }
        }
        return false;
    }

    public function all(): array
    {
        return $this->notes;
    }

    public function find(int $id): ?Note
    {
        foreach ($this->notes as $note) {
            if ($note->getId() === $id) {
                return $note;
            }
        }
        return null;
    }

    public function count(): int
    {
        return count($this->notes);
    }
}