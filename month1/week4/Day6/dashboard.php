<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="zh-TW">
    <head>
        <meta charset="UTF-8">
        <title>Dashboard</title>
    </head>
    <body>
        <p>Hello, <?= htmlspecialchars($_SESSION["username"]) ?></p>
        <p>You are <?= htmlspecialchars($_SESSION["role"]) ?></p>
        <a href="logout.php">Logout</a>
    </body>
</html>