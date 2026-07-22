<?php
class Todo {
    public int $id;
    public string $title;
    public bool $done;

    public function __construct($id, $title, $done = false) {
        $this->id = $id;
        $this->title = $title;
        $this->done = $done;
    }
    public function markDone() { $this->done = true; }
    public function markPending() { $this->done = false; }
    public function toggle() { $this->done = !$this->done; }
    public function isDone() { return $this->done; }
}