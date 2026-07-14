<?php
$message = "";

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
    
    // 處理 POST（delete / toggle_role）
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $action = $_POST["action"] ?? "";
        $id = $_POST["id"] ?? 0;
        
        if ($action === "delete") {
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$id]);
            $message = "Deleted user id $id";
        } elseif ($action === "toggle_role") {
            $stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
            $stmt->execute([$id]);
            $user = $stmt->fetch();

            $newRole = ($user["role"] === "user") ? "admin" : "user";
            $stmt = $pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
            $stmt->execute([$newRole, $id]);
        }
    }
    
    // 讀所有用戶
    $stmt = $pdo->prepare("SELECT id, username, role FROM users");
    $stmt->execute();
    $users = $stmt->fetchAll();
    $message = "Updated user id $id role to $newRole";
    
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html>
    <body>
        <h1>User Management</h1>

        <?php if ($message): ?>
            <p style="color:green;"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user["id"] ?></td>
                        <td><?= htmlspecialchars($user["username"]) ?></td>
                        <td><?= htmlspecialchars($user["role"]) ?></td>
                        <td>
                            <!-- Toggle Role form -->
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="action" value="toggle_role">
                                <input type="hidden" name="id" value="<?= $user["id"] ?>">
                                <button type="submit">Toggle Role</button>
                            </form>
                            
                            <!-- Delete form -->
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $user["id"] ?>">
                                <button type="submit" onclick="return confirm('Delete?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </body>
</html>