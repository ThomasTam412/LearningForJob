<?php

namespace App\Core;

use PDO;

class Database
{
    public static function connect(array $config): PDO
    {
        $dsn = "mysql:host=" . $config["host"]
             . ";dbname=" . $config["name"]
             . ";charset=utf8mb4";

        return new PDO(
            $dsn, 
            $config["user"],
            $config["pass"],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ],
        );
    }
}