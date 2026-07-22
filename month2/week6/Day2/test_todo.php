<?php
require_once "Todo.php";

$t = new Todo(1, "Buy milk");

echo $t->title . "\n";           // Buy milk
echo ($t->isDone() ? "Yes" : "No") . "\n";  // No

$t->markDone();
echo ($t->isDone() ? "Yes" : "No") . "\n";  // Yes

$t->toggle();
echo ($t->isDone() ? "Yes" : "No") . "\n";  // No