<?php
session_start();
?>
<!DOCTYPE html>
<html lang="zh-TW">
    <head>
        <meta charset="UTF-8">
        <title>Home</title>
    </head>
    <body>
        <?php if(isset($_SESSION["username"])): ?>
            <h1>Welcome back, <?= htmlspecialchars($_SESSION["username"]) ?>(<?= htmlspecialchars($_SESSION["role"]) ?>).</h1>
            <a href="dashboard.php">Go to Dashboard</a>
            <a href="logout.php">Logout</a>
        <?php else : ?>
            <h1>Welcome, Guest.</h1>
            <a href="login.php">Login</a>
        <?php endif; ?>
    </body>
</html>