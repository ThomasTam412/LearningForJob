<?php
require_once "db.php";
// 防止未登錄進入
if (!isset($_SESSION["uid"])) {
    set_flash("error", "請先登錄");
    header("Location: mylogin.php");
    exit;
}
$successMessage = get_flash("success");
?>
<!DOCTYPE html>
<html>
    <body>
        <?php if ($successMessage): ?>
            <p style="color:green;"><?= htmlspecialchars($successMessage) ?></p>
        <?php endif; ?>
        <h1>歡迎用戶: <?= htmlspecialchars($_SESSION["uname"]) ?></h1>
        <p>ID: <?= htmlspecialchars($_SESSION["uid"]) ?></p>
        <p>權限: <?= htmlspecialchars($_SESSION["urole"]) ?></p>

        <p><a href="myhome.php">首頁</a></p>
        <p><a href="mylogout.php">登出</a></p>
     </body>
</html>