<?php
session_start();
try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=learning_db;charset=utf8mb4",
        "root",
        "",
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    die("DB Connection failed: " . $e->getMessage());
}

function set_flash($type, $message) {
    $_SESSION["flash"][$type] = $message;
}

function get_flash($type) {
    if (isset($_SESSION["flash"][$type])) {
        $message = $_SESSION["flash"][$type];
        unset($_SESSION["flash"][$type]);
        return $message;
    }
    return null;
}