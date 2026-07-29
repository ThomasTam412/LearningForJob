<?php
class Todo 
{
    public function __construct(
        private ?int $id,
        private string $title,
        private bool $done = false,
        private ?string $createdAt = null
    ) {}
    public function toggle(): void 
    {
        $this->done = !$this->done;
    }
    public function setId(int $id): void 
    {
         $this->id = $id;
    }
    public function getId(): ?int 
    {
        return $this->id;
    }
    public function getTitle(): string 
    {
        return $this->title;
    }
    public function isDone(): bool 
    {
        return $this->done;
    }
    public function getCreatedAt(): ?string 
    {
        return $this->createdAt;
    }
}