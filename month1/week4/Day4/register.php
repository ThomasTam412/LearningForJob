<?php
$errors = [];
$username = "";
$email = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $email = trim($_POST["email"] ?? "");

    // 後端驗證輸入
    if ($username === "") {
        $errors[] = "必須填寫用戶名";
    } 
    if ($email === "") {
        $errors[] = "必須填寫電郵";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "電郵格式錯誤";
    }

    // 通過驗證
    if (empty($errors)) {
        echo "註冊成功!";
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>註冊</title>
    </head>
    <body>
        <h2>註冊表單</h2>
    <!-- 顯示錯誤 -->
    <?php if(!empty($errors)): ?>
        <ul style="color: red;">
            <?php foreach($errors as $error) :?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach;?>
        </ul>
    <?php endif;?>

    <form method="post">
        <label for="username">使用者名稱:</label>
        <input type="text" id="username" autocomplete="username" name="username" value="<?= htmlspecialchars($username) ?>" required>
        <br>
        <label for="email">電郵:</label>
        <input type="email" id="email" autocomplete="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
        <br>
        <button type="submit">提交</button>
    </form>
    </body>
</html>