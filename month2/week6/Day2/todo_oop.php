<?php
require_once "Todo.php";
require_once "TodoList.php";

// 建 list + hardcoded data
$list = new TodoList();
$list->add(new Todo(1, "Buy milk"));
$list->add(new Todo(2, "Study PHP", true));
$list->add(new Todo(3, "Write blog"));
$list->add(new Todo(4, "打籃球", true));
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