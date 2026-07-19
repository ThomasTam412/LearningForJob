<?php
require_once "db.php";
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}
$successMessage = get_flash("success");
?>
<!DOCTYPE html>
<html>
    <body>
        <h1>Dashboard</h1>

        <?php if ($successMessage): ?>
            <p style="color: green;"><?= htmlspecialchars($successMessage) ?></p>
        <?php endif; ?>
        <p>Hello, <?= htmlspecialchars($_SESSION["username"]) ?>!</p>
        <p>Your role: <?= htmlspecialchars($_SESSION["role"]) ?></p>
        <p>Your ID: <?= htmlspecialchars($_SESSION["user_id"]) ?></p>

        <p><a href="home.php">Home</a></p>
        <p><a href="logout.php">Logout</a></p>
    </body>
</html>