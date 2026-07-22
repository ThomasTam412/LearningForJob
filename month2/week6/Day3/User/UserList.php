<?php
require_once "User.php";
class UserList {
    public array $users = [];
    public function add(User $user): void {
        $this->users[] = $user;
    }
    public function find(int $id): ?User {
        foreach ($this->users as $user){
            if ($user->id === $id) {
                return $user;
            }
        }
        return null;
    }
    public function all(): array {
        return $this->users;
    }
    public function count(): int {
        return count($this->users);
    }
    public function countAdmins(): int {
        return count(array_filter($this->users, fn($u) => $u->isAdmin()));
    }
}