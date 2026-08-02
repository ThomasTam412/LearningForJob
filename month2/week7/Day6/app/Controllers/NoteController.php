<?php

namespace App\Controllers;

use App\Models\Note;
use App\Repositories\NoteRepositoryInterface;

class NoteController
{
    public function __construct(
        private NoteRepositoryInterface $repo,
    ) {}

    public function index(): void
    {
        $notes = $this->repo->findAll();
        foreach ($notes->all() as $note) {
            echo "Id: " . $note->getId() . "  ";
            echo "Title: " . htmlspecialchars($note->getTitle()) . "  ";
            echo ($note->isPinned() ? "Pinned" : "Unpinned") . "  ";
            echo "Body: " . nl2br(htmlspecialchars($note->getBody())) . "<br>";
            echo "Created At: " . $note->getCreatedAt() . "<br>";
            echo '<form method="POST" action="/notes/delete">';
            echo '<input type="hidden" name="id" value="' . $note->getId() . '">';
            echo '<button>刪除</button>';
            echo '</form>';
            echo '<form method="POST" action="/notes/toggle">';
            echo '<input type="hidden" name="id" value="' . $note->getId() . '">';
            echo '<button>切換</button>';
            echo '</form>';
        }
        echo '<form method="POST" action="/notes">';
        echo '<input name="title">';
        echo '<textarea name="body"></textarea>';
        echo '<button>新增</button>';
        echo '</form>';
    }

    public function store(): void
    {
        $title = trim($_POST["title"] ?? "");
        $body = trim($_POST["body"] ?? "");
        $note = new Note(null, $title, $body);
        $this->repo->save($note);
        header("Location: /notes");
        exit;
    }

    public function delete(): void
    {
        $id = (int) ($_POST["id"] ?? 0);
        $this->repo->delete($id);
        header("Location: /notes");
        exit;
    }

    public function toggle(): void
    {
        $id = (int) ($_POST["id"] ?? 0);
        $note = $this->repo->findById($id);
        if (!$note) {
            return;
        }
        $note->toggle();
        $this->repo->save($note);
        header("Location: /notes");
        exit;
    }
}