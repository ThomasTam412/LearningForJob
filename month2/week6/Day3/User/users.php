<?php
require_once "User.php";
require_once "UserList.php";
require_once "db.php";

$list = new UserList();
$stmt = $pdo->prepare("SELECT id, username, role, created_at FROM users");
$stmt->execute();
$rows = $stmt->fetchAll();

foreach ($rows as $row) {
    $list->add(new User(
        (int)$row["id"],
        $row["username"],
        $row["role"],
        $row["created_at"]
    ));
}
?>
<!DOCTYPE html>
<html lang="en">
    <body>
        <h1>User List</h1>
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>CreatedAt</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($list->all() as $user): ?>
                    <tr>
                        <td><?= $user->id ?></td>
                        <td><?= htmlspecialchars($user->username) ?></td>
                        <td><?= htmlspecialchars($user->role) ?></td>
                        <td><?= htmlspecialchars($user->createdAt) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <p>Total: <?= $list->count() ?></p>
        <p>Admin: <?= $list->countAdmins() ?></p>
    </body>
</html>