<?php

namespace App\Models;

class Note
{
    public function __construct(
        private ?int $id,
        private string $title,
        private string $body = "",
        private bool $pinned = false,
        private ?string $createdAt = null,
    ) {}

    public function toggle(): void
    {
        $this->pinned = !$this->pinned;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function isPinned(): bool
    {
        return $this->pinned;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}