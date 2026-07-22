 <?php
require_once "db.php";
require_once "Todo.php";
require_once "TodoList.php";
$list = new TodoList();
$stmt = $pdo->prepare("SELECT id, title, is_done FROM todos");
$stmt->execute();
$rows = $stmt->fetchAll();
foreach ($rows as $row) {
    $list->add(new Todo(
        $row["id"],
        $row["title"],
        (bool)$row["is_done"]
    ));
}
?>
<!DOCTYPE html>
<html>
<body>
    <h1>Todo List (OOP)</h1>
    
    <p>Total: <?= $list->count() ?> | 
       Done: <?= $list->countDone() ?> | 
       Pending: <?= $list->countPending() ?></p>
    
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($list->all() as $todo): ?>
                <tr>
                    <td><?= $todo->id ?></td>
                    <td>
                        <?php if ($todo->isDone()): ?>
                            <s><?= htmlspecialchars($todo->title) ?></s>
                        <?php else: ?>
                            <?= htmlspecialchars($todo->title) ?>
                        <?php endif; ?>
                    </td>
                    <td><?= $todo->isDone() ? "✅ Done" : "⬜ Pending" ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>