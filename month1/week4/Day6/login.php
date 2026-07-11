<?php
session_start();
$users = [
    "thomas" => ["password" => "1234", "role" => "admin"],
    "alice"  => ["password" => "abcd", "role" => "user"],
    "bob"    => ["password" => "0000", "role" => "user"],
];
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $inputUsername = $_POST["username"] ?? "";
    $inputPassword = $_POST["password"] ?? "";
    if (isset($users[$inputUsername]) && $users[$inputUsername]["password"] === $inputPassword) {
        $_SESSION["username"] = $inputUsername;
        $_SESSION["role"] = $users[$inputUsername]["role"];
        header ("Location: dashboard.php");
        exit;
    } else {
        $errorMessage = "Username or password incorrect";
    }
}
?>
<!DOCTYPE html>
<html lang="zh-TW">
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
    </head>
    <body>
        <h1>Login</h1>
        <?php if(isset($errorMessage)): ?>
            <p style="color: red;"><?= htmlspecialchars($errorMessage) ?></p>
        <?php endif; ?>
        <form method="post" action="login.php">
            <label>
                用戶名: 
                <input type="text" name="username">
            </label>
            <label>
                密碼: 
                <input type="password" name="password">
            </label>
            <button type="submit">提交</button>
        </form>
    </body>
</html>