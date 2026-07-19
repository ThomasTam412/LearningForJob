<?php
require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";
    $passwordConfirm = $_POST["password_confirm"] ?? "";

    if ($password !== $passwordConfirm) {
        set_flash("error", "Passwords do not match");
        header("Location: register.php");
        exit;
    }

    if (strlen($password) < 6) {
        set_flash("error", "Password must be at least 6 characters");
        header("Location: register.php");
        exit;
    }

    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
        set_flash("error", "Username already taken");
        header("Location: register.php");
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->execute([$username, $hashedPassword, "user"]);

    set_flash("success", "Registration successful. Please login.");
    header("Location: login.php");
    exit;
}
$errorMessage = get_flash("error");
?>
<!DOCTYPE html>
<html>
    <body>
        <h1>Register</h1>
        <?php if ($errorMessage): ?>
            <p style="color: red;"><?= htmlspecialchars($errorMessage) ?></p>
        <?php endif; ?>
        <form method="POST">
            <label>
                用戶名:
                <input type="text" name="username" required>
            </label>
            <label>
                密碼:
                <input type="password" name="password" required>
            </label>
            <label>
                確認密碼:
                <input type="password" name="password_confirm" required>
            </label>
            <button type="submit">註冊</button>
        </form>
        <p><a href="login.php">Login</a></p>
    </body>
</html>
