<?php
require_once "db.php";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";
    $passwordConfirm = $_POST["passwordConfirm"] ?? "";
    
    // 檢查用戶是否已存在
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
        set_flash("error", "該用戶名已被使用");
        header("Location: myregister.php");
        exit;
    }

    // 檢查密碼長度
    if (strlen($password) < 8) {
        set_flash("error", "密碼至少8位");
        header("Location: myregister.php");
        exit;
    }

    // 兩次確認密碼
    if ($password !== $passwordConfirm) {
        set_flash("error", "密碼不一致");
        header("Location: myregister.php");
        exit;
    }

    // 上傳新用戶到數據庫
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // 密碼加密
    $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->execute([$username, $hashedPassword, "user"]);

    set_flash("success", "註冊成功");
    header("Location: mylogin.php");
    exit;
}
$errorMessage = get_flash("error");
?>
<!DOCTYPE html>
<html>
    <body>
        <h1>註冊</h1>

        <?php if ($errorMessage): ?>
            <p style="color:red;"><?= htmlspecialchars($errorMessage) ?></p>
        <?php endif; ?>
        <form method="post">
            <label for="username">用戶名:</label>
            <input type="text" name="username" required>

            <label for="password">密碼:</label>
            <input type="password" name="password" required>

            <label for="passwordConfirm">確認密碼:</label>
            <input type="password" name="passwordConfirm" required>

            <button type="submit">註冊</button>
        </form>

        <p><a href="myhome.php">首頁</a></p>
        <p><a href="mylogin.php">登入</a></p>
    </body>
</html>