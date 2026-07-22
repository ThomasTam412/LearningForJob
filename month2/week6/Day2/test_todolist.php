<?php
require_once "Todo.php";
require_once "TodoList.php";

$list = new TodoList();

$list->add(new Todo(1, "Buy milk"));
$list->add(new Todo(2, "Study PHP", true));   // 已完成
$list->add(new Todo(3, "Write blog"));

echo "Total: " . $list->count() . "\n";              // 3
echo "Done: " . $list->countDone() . "\n";           // 1
echo "Pending: " . $list->countPending() . "<br>";     // 2

$found = $list->find(2);
echo "Found id 2: " . $found->title . "<br>";          // Study PHP

$notFound = $list->find(999);
var_dump($notFound);                                  // NULL

$list->remove(1);
echo "After remove id 1, total: " . $list->count() . "<br>";  // 2 

foreach ($list->all() as $t) {
    echo "- " . $t->title . "<br>";
}