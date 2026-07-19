<?php
require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($username === "" || $password === "") {
        set_flash("error", "Username and password required");
        header("Location: login.php");
        exit;
    }

    $stmt = $pdo->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user["password"])) {
        set_flash("error", "Invalid username or password");
        header("Location: login.php");
        exit;
    }

    session_regenerate_id(true);
    $_SESSION["user_id"] = $user["id"];
    $_SESSION["username"] = $user["username"];
    $_SESSION["role"] = $user["role"];

    set_flash("success", "Welcome back, " . $user["username"]);
    header("Location: dashboard.php");
    exit;
}
$errorMessage = get_flash("error");
$successMessage = get_flash("success");
?>
<!DOCTYPE html>
<html>
    <body>
        <h1>Login Form</h1>

        <!-- Message Part -->
        <?php if ($errorMessage): ?>
            <p style="color: red;"><?= htmlspecialchars($errorMessage) ?></p>
        <?php endif; ?>
        <?php if ($successMessage): ?>
            <p style="color: green;"><?= htmlspecialchars($successMessage) ?></p>
        <?php endif; ?>

        <!-- Form Part -->
        <form method="POST">
            <label>
                用戶名:
                <input type="text" name="username" required>
            </label>
            <label>
                密碼:
                <input type="password" name="password" required>
            </label>
            <button type="submit">登錄</button>
        </form>
        <p><a href="register.php">Register</a></p>
    </body>
</html>