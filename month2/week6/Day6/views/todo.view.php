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
                <?php foreach ($todos->all() as $todo): ?>
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
                        <td><?= htmlspecialchars($todo->createdAt) ?></td>

                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="action" value="toggle">
                                <input type="hidden" name="id" value="<?= $todo->id ?>">
                                <button type="submit"><?= $todo->isDone() ? "Undo" : "Done"?></button>
                            </form>

                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?= $todo->id ?>">
                                <button type="submit" onclick="return confirm('Delete?')">Delete</button>
                            </form>
                        </td>
                        
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>    
    </body>
</html>