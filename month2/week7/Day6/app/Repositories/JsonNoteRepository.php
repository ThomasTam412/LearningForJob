<?php
namespace App\Repositories;

use App\Models\Note;
use App\Models\NoteList;

class JsonNoteRepository implements NoteRepositoryInterface
{
    public function __construct(
        private string $filePath,
    ) {}


    public function findAll(): NoteList
    {
        $rows = $this->readAll();
        $notes = new NoteList();
        foreach ($rows as $row) {
            $notes->add($this->hydrate($row));
        }
        return $notes;
    }

    public function findById(int $id): ?Note
    {
        return $this->findAll()->find($id);
    }

    public function save(Note $note): void
    {
        $rows = $this->readAll();
        if ($note->getId() === null) {
            $ids = array_column($rows, "id");
            $newId = count($ids) ? max($ids) + 1 : 1;
            $note->setId($newId);
            if ($note->getCreatedAt() === null) {
                $note->setCreatedAt(date("Y-m-d H:i:s"));
            }
            $row = $this->dehydrate($note);
            $rows[] = $row;
            $this->writeAll($rows);
            return;
        }

        foreach ($rows as $index => $row) {
            if ((int) $row["id"] === $note->getId()) {
                $newRow = $this->dehydrate($note);
                $rows[$index] = $newRow;
                $this->writeAll($rows);
                return;
            }
        }
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

    private function hydrate(array $row): Note
    {
        return new Note(
            (int) $row["id"],
            $row["title"],
            $row["body"],
            (bool) $row["is_pinned"],
            $row["created_at"],
        );
    }

    private function dehydrate(Note $note): array
    {
        return [
            "id" => $note->getId(),
            "title" => $note->getTitle(),
            "body" => $note->getBody(),
            "is_pinned" => $note->isPinned(),
            "created_at" => $note->getCreatedAt(),
        ];
    }

    private function readAll(): array
    {
        if (file_exists($this->filePath)) {
            $json = file_get_contents($this->filePath);
            return json_decode($json, true) ?? [];
        }
        return [];
    }

    private function writeAll(array $rows): void
    {
        $json = json_encode($rows, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        file_put_contents($this->filePath, $json);
    }
}