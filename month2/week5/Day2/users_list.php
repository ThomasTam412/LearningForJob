<?php
try {
    // 1. 連線
    $pdo = new PDO(
        "mysql:host=localhost;dbname=learning_db;charset=utf8mb4",
        "root",
        "",
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
    echo "Connected!<br>";
    
    // 2. 準備 + 執行
    $stmt = $pdo->prepare("SELECT id, username, role, created_at FROM users");
    $stmt->execute();
    
    // 3. 攞資料
    $users = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>
    <head><title>Users</title></head>
    <body>
        <h1>User List</h1>
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Created</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user["id"]) ?></td>
                        <td><?= htmlspecialchars($user["username"]) ?></td>
                        <td><?= htmlspecialchars($user["role"]) ?></td>
                        <td><?= htmlspecialchars($user["created_at"]) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </body>
</html>