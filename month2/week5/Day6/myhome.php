<?php
require_once "db.php";
$successMessage = get_flash("success");
?>
<!DOCTYPE html>
<html>
    <body>
        <?php if ($successMessage): ?>
            <p style="color:green"><?= htmlspecialchars($successMessage) ?></p>
        <?php endif; ?>

        <?php if (!isset($_SESSION["uid"])): ?>
            <h1>你好，請先登入</h1>
            <p><a href="mylogin.php">登入</a></p>
            <p><a href="myregister.php">註冊</a></p>
        <?php else: ?>
            <h1>你好,<?= htmlspecialchars($_SESSION["uname"]) ?></h1>
            <p><a href="mydashboard.php">主頁</a></p>
            <p><a href="mylogout.php">登出</a></p>
        <?php endif; ?>
    </body>
</html>