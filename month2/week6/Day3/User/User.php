<?php
class User {
    public function __construct(
        public int $id,
        public string $username,
        public string $role,
        public string $createdAt
    ) {}
    public function isAdmin(): bool {
        return in_array($this->role, ["admin", "super_admin"]);
    }
}