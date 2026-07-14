<?php
$successMessage = "";
$errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $inputUsername = $_POST["username"] ?? "";
    $inputPassword = $_POST["password"] ?? "";
    $inputRole = $_POST["role"] ?? "user";

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

        $sql = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$inputUsername, $inputPassword, $inputRole]);

        $newId = $pdo->lastInsertId();
        $successMessage = "New user created, ID: $newId";
    } catch (PDOException $e) {
        $errorMessage = "Failed : " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html>
<body>
    <h1>Add User</h1>
    
    <?php if ($successMessage): ?>
        <p style="color:green;"><?= htmlspecialchars($successMessage) ?></p>
    <?php endif; ?>
    
    <?php if ($errorMessage): ?>
        <p style="color:red;"><?= htmlspecialchars($errorMessage) ?></p>
    <?php endif; ?>
    
    <form method="POST">
        Username: <input type="text" name="username" required><br>
        Password: <input type="text" name="password" required><br>
        Role: 
        <select name="role">
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select><br>
        <button type="submit">Add</button>
    </form>
    
    <p><a href="users_list.php">← View all users</a></p>
</body>
</html>