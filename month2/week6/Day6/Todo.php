<?php
class Todo {
    public function __construct(
        public int $id,
        public string $title,
        public bool $done = false,
        public string $createdAt = ""
    ) {}
    public function markDone() { $this->done = true; }
    public function markPending() { $this->done = false; }
    public function toggle() { $this->done = !$this->done; }
    public function isDone() { return $this->done; }
}