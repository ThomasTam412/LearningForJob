<?php
require 'autoload.php';
$repo = new JsonTodoRepository(__DIR__ . '/todos.json');

// A. 測 INSERT + setId 回填
$todo = new Todo(null, "測試 idempotent");
var_dump($todo->getId());          // NULL
$repo->save($todo);
var_dump($todo->getId());          // int(某數字)  ← setId 生效

// B. 測 idempotency（重點）
$id = $todo->getId();
$before = $repo->findById($id)->isDone();

$repo->save($todo);
$repo->save($todo);
$repo->save($todo);

$after = $repo->findById($id)->isDone();
var_dump($before === $after);      // 必須 true

// C. 測 toggle 流程
$t = $repo->findById($id);
$t->toggle();
$repo->save($t);
var_dump($repo->findById($id)->isDone());   // 應該同 $before 相反

// D. 清走
// $repo->delete($id);
// var_dump($repo->findById($id));    // NULL