<?php
require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // 取輸入
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    // 判空輸入
    if ($username === "" || $password === "") {
        set_flash("error", "用戶名與密碼都不能為空");
        header("Location: mylogin.php");
        exit;
    }

    // 取用戶
    $stmt = $pdo->prepare("SELECT id, username, role FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    // 判用戶存在和密碼正確
    if (!$user || password_verify($password, $user["password"])) {
        set_flash("error", "用戶名或密碼錯誤");
        header("Location: mylogin.php");
        exit;
    }

    // SESSION 存資料用於其他頁面
    session_regenerate_id(true);
    $_SESSION["uid"] = $user["id"];
    $_SESSION["uname"] = $user["username"];
    $_SESSION["urole"] = $user["role"];

    set_flash("success", "登錄成功");
    header("Location: mydashboard.php");
    exit;
}
$successMessage = get_flash("success");
$errorMessage = get_flash("error");
?>
<!DOCTYPE html>
<html>
    <body>
        <h1>My Login System</h1>

        <!-- Message Part -->
         <?php if ($successMessage): ?>
            <p style="color:green;"><?= htmlspecialchars($successMessage) ?></p>
        <?php endif; ?>
        <?php if ($errorMessage): ?>
            <p style="color:red;"><?= htmlspecialchars($errorMessage) ?></p>
        <?php endif; ?>

        <!-- Form Part -->
        <form method="post">
            <label for="username">用戶名:</label>
            <input type="text" name="username" required>

            <label for="password">密碼:</label>
            <input type="password" name="password" required>

            <button type="submit">登錄</button>

            <p><a href="myhome.php">首頁</a></p>
            <p><a href="myregister.php">註冊</a></p>
        </form>
    </body>
</html>