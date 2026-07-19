<?php
require_once "db.php";
$successMessage = get_flash("success");
?>
<!DOCTYPE html>
<html>
    <body>
        <?php if (!isset($_SESSION["user_id"])): ?>
            <h1>Welcome, Guest.</h1>

            <?php if ($successMessage): ?>
                <p style="color: green;"><?= htmlspecialchars($successMessage) ?></p>
            <?php endif; ?>

            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php else: ?>
            <h1>Welcome back, <?= htmlspecialchars($_SESSION["username"]) ?>! (<?= htmlspecialchars($_SESSION["role"]) ?>)</h1>
            <a href="dashboard.php">Go to dashboard</a>
            <a href="logout.php">Logout</a>
        <?php endif; ?>
    </body>
</html>