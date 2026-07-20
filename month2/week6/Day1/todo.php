<?php
require_once "db.php";
$flash = new Flash();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST["action"] ?? "";
    $id = $_POST["id"] ?? "";
    $title = trim($_POST["title"] ?? "");

    if ($action === "add") {
        if ($title === "") {
            $flash->set("error", "Title cannot be empty");
            header("Location: todo.php");
            exit;
        } else {
            $flash->set("success", "Added: $title");
            $stmt = $pdo->prepare("INSERT INTO todos (title) VALUES (?)");
            $stmt->execute([$title]);
            header("Location: todo.php");
            exit;
        }
    } elseif ($action === "toggle") {
        $stmt = $pdo->prepare("UPDATE todos SET is_done = NOT is_done WHERE id = ?");
        $stmt->execute([$id]);
        $flash->set("success", "Todo id $id updated");
        header("Location: todo.php");
        exit;
    } elseif ($action === "delete") {
        $stmt = $pdo->prepare("DELETE FROM todos WHERE id = ?");
        $stmt->execute([$id]);
        $flash->set("success", "Todo id $id deleted");
        header("Location: todo.php");
        exit;
    }
}

$stmt = $pdo->prepare("SELECT id, title, is_done, created_at FROM todos");
$stmt->execute();
$todos = $stmt->fetchAll();

$total = count($todos);
$done = 0;
foreach ($todos as $todo) {
    if ($todo["is_done"]) $done++;
}
$pending = $total - $done;

$successMessage = $flash->get("success");
$errorMessage = $flash->get("error");
?>
<!DOCTYPE html>
<html>
    <body>
        <h1>Todos List</h1>

        <p>Total: <?= $total ?> | Done: <?= $done ?> | Pending: <?= $pending ?></p>

        <?php if ($successMessage): ?>
            <p style="color:green;"><?= htmlspecialchars($successMessage) ?></p>
        <?php endif; ?>
    
        <?php if ($errorMessage): ?>
            <p style="color:red;"><?= htmlspecialchars($errorMessage) ?></p>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="action" value="add">
            <input type="text" name="title">
            <button type="submit">Add</button>
        </form>
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Created</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($todos as $todo): ?>
                    <tr>
                        <td><?= $todo["id"] ?></td>
                        <td>
                            <?php if ($todo["is_done"]): ?>
                                <s><?= htmlspecialchars($todo["title"]) ?></s>
                            <?php else: ?>
                                <?= htmlspecialchars($todo["title"]) ?>
                            <?php endif; ?>
                        </td>
                        <td><?= $todo["is_done"] ? "✅ Done" : "⬜ Pending" ?></td>
                        <td><?= htmlspecialchars($todo["created_at"]) ?></td>

                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="action" value="toggle">
                                <input type="hidden" name="id" value="<?= $todo["id"] ?>">
                                <button type="submit"><?= $todo["is_done"] ? "Undo" : "Done"?></button>
                            </form>

                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $todo["id"] ?>">
                                <button type="submit" onclick="return confirm('Delete?')">Delete</button>
                            </form>
                        </td>
                        
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>    
    </body>
</html>