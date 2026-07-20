<?php
require_once "db.php";

$flash = new Flash();
$flash->set("success", "Hello from OOP!");
$flash->set("error", "This is an error");
?>
<!DOCTYPE html>
<html>
<body>
    <h1>Flash Test</h1>
    
    <?php $success = $flash->get("success"); ?>
    <?php if ($success): ?>
        <p style="color:green;"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>
    
    <?php $error = $flash->get("error"); ?>
    <?php if ($error): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    
    <hr>
    
    <p>After get, has success? <?= $flash->has("success") ? "Yes" : "No" ?></p>
    <p>After get, has error? <?= $flash->has("error") ? "Yes" : "No" ?></p>
</body>
</html>