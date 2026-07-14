<?php
$user = null;
$searched = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $inputUsername = $_POST["username"] ?? "";
    $seached = true;

    try {
        $pdo = new PDO(
            "mysql:host=localhost;dbname=learning_db;charset=utf8mb4",
            "root",
            "",
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );

        // 準備 + 執行
        $sql = "SELECT id, username, role, created_at FROM users WHERE username = '$inputUsername'";
        $stmt = $pdo->query($sql);

        // 攞資料
        $user = $stmt->fetch();
    } catch(PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html>
    <head><title>User Search</title></head>
    <body>
        <h1>User Search</h1>
        <form method="POST">
            <label>
                用戶名:
                <input type="text" name="username" placeholder="Username">
            </label>
            <button type="submit">Search</button>
        </form>

        <?php if ($searched): ?>
            <?php if ($user): ?>
                <p>ID: <?= $user["id"] ?>, Username: <?= htmlspecialchars($user["username"]) ?>, Role: <?= htmlspecialchars($user["role"]) ?>, Created: <?= htmlspecialchars($user["created_at"]) ?></p>
            <?php else: ?>
                <p>User not found</p>
            <?php endif; ?>
        <?php endif; ?>
    </body>
</html>
