<?php
namespace App\Controllers;
use PDO;

class HomeController
{
    public function __construct(
        private PDO $pdo,
    ) {}

    public function index(): void
    {
        echo "Hello Blog";

        $stmt = $this->pdo->query("SELECT COUNT(*) FROM users");
        echo "Users: " . $stmt->fetchColumn();
    }
}