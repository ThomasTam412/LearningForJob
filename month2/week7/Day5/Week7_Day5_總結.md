# Week 7 Day 5 總結 — Router / Front Controller

**日期：** 2026-07-31
**主題：** Router、Front Controller、Controller + DI
**產出：** `month2/week7/Day5/` — 完整 mini framework

---

## 今日一句話

> **Router = 一張表，將「URL + HTTP method」對應到「邊個 function」。**

---

## 解決咗咩問題

**之前：一個功能一個檔**
```
todo.php  todo_add.php  todo_delete.php  todo_toggle.php  login.php
```

| 問題 | 具體情況 |
|---|---|
| 重複 code | 每個檔都要 `require` + `session_start()` + check 登入 |
| 冇全局視野 | 想知個 app 有咩功能，要逐個檔開嚟睇 |
| URL 樣衰 | `todo_delete.php?id=5` |
| 檔案結構 = URL 結構 | 改網址就要改檔名，所有 link 一齊死 |
| 加中間層好難 | 「全站要登入」→ 每個檔手動加一次 |

**之後：Front Controller —— 所有 request 只入一個檔**

```
GET  /todos  →  public/index.php  →  查表  →  TodoController::index()
POST /todos  →  public/index.php  →  查表  →  TodoController::store()
```

---

## 最終結構

```
week7/Day5/
├── public/
│   └── index.php                    ← 唯一入口
├── app/
│   ├── Controllers/
│   │   └── TodoController.php       App\Controllers\TodoController
│   ├── Models/
│   │   ├── Todo.php
│   │   └── TodoList.php
│   └── Repositories/
│       ├── TodoRepositoryInterface.php
│       ├── JsonTodoRepository.php
│       └── PdoTodoRepository.php
├── autoload.php
├── routes.php                       ← 路由表
└── todos.json
```

---

## 三個檔案

### `routes.php` — 一張表列晒成個 app

```php
<?php

use App\Controllers\TodoController;

return [
    "GET /todos"  => [TodoController::class, "index"],
    "POST /todos" => [TodoController::class, "store"],
];
```

**⚠️ 用 `return` 一個 array，唔係 `$routes = [...]`。**

咁樣入口檔就寫得 `$routes = require 'routes.php';` ——
`require` 會攞到嗰個 return 值。呢個係 PHP config 檔嘅標準做法，Laravel 全部 config 都係咁。

`TodoController::class` 會展開成字串 `"App\Controllers\TodoController"`（配合 `use`）。

---

### `public/index.php` — Front Controller

```php
<?php

require __DIR__ . '/../autoload.php';

use App\Repositories\JsonTodoRepository;

$repo = new JsonTodoRepository(__DIR__ . '/../todos.json');
$routes = require __DIR__ . '/../routes.php';

$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);   // ⚠️ 拆走 query string
$key = $method . " " . $uri;

if (isset($routes[$key]) === false) {
    http_response_code(404);
    echo "404 Not Found";
    return;
}

[$class, $action] = $routes[$key];      // array destructuring
$controller = new $class($repo);        // 用字串 new + DI
$controller->$action();                 // 用字串 call method
```

#### ⭐ 最後三行 = 所有 PHP framework 嘅心臟

```php
[$class, $action] = $routes[$key];
```
`["App\Controllers\TodoController", "index"]` 一次過拆成兩個變數。
（C++ 17 structured bindings 同一個概念）

```php
$controller = new $class($repo);
```
**Variable class name** — `$class` 係字串，PHP 容許用字串 `new`。
autoloader 就係喺呢一刻出動。
`$repo` 就係 **Dependency Injection** —— Controller 唔使自己砌 repository。

```php
$controller->$action();
```
**Variable method name** — 同理。

> Laravel 做嘅嘢複雜好多（middleware、參數自動注入、route model binding），
> 但核心就係「由設定砌出 controller 然後 call」。

---

### `app/Controllers/TodoController.php`

```php
<?php

namespace App\Controllers;

use App\Models\Todo;
use App\Repositories\TodoRepositoryInterface;   // ⚠️ interface 唔係實作

class TodoController
{
    public function __construct(
        private TodoRepositoryInterface $repo,
    ) {}

    public function index(): void
    {
        $todos = $this->repo->findAll()->all();
        foreach ($todos as $todo) {
            echo "Id: " . $todo->getId();
            echo " Title: " . htmlspecialchars($todo->getTitle());   // 只有用戶輸入要 escape
            echo " " . ($todo->isDone() ? "Done" : "Pending");
            echo " Created At: " . $todo->getCreatedAt();
            echo "<br>";
        }

        echo '<form method="POST" action="/todos">';
        echo '<input name="title">';
        echo '<button>新增</button>';
        echo '</form>';
    }

    public function store(): void
    {
        $title = trim($_POST["title"] ?? "");       // defensive
        $todo = new Todo(null, $title);             // 製造喺 Controller 做
        $this->repo->save($todo);
        header("Location: /todos");                 // PRG，用 URL 唔用檔名
        exit;                                       // ⚠️ 必須
    }
}
```

---

## 🐛 今日撞過嘅坑

### ① `store()` 入面重複 check method

```php
if ($_SERVER["REQUEST_METHOD"] === "POST") {    // ❌ 永遠 true，死 code
```

**Router 已經分流咗。** `"POST /todos" => store` 意味住只有 POST 先入到嚟。

> 呢個係舊思維殘留：以前一個檔處理 GET + POST，所以要自己分。
> 有咗 router，**每個 method 只需要處理一種情況**。

### ② Redirect 用檔名

```php
header("Location: index.php");    // ❌ 404
header("Location: /todos");       // ✅
```

> **Router 之後，成個 app 只有 URL，冇檔案路徑。**
> 所有 `href` / `action` / `Location` 都寫 URL。

### ③ `htmlspecialchars` 用喺唔需要嘅地方

```php
htmlspecialchars($todo->getId())          // int，唔使
htmlspecialchars($todo->getCreatedAt())   // 系統生成，唔使
                                          // 而且係 ?string，null 落去 PHP 8.1+ 有 deprecated warning
htmlspecialchars($todo->getTitle())       // ✅ 呢個先係用戶輸入
```

### ④ 變數撞名

```php
$method = $_SERVER['REQUEST_METHOD'];   // "GET"
[$class, $method] = $routes[$key];      // ← 覆蓋咗
```
→ 第二個改名做 `$action`。

### ⑤ `PDO` 喺 namespace 入面搵唔到

```
Undefined type 'App\Repositories\PDO'
```

Day 4 提過嘅坑，今日撞正：**function 有 fallback，class 冇。**

```php
use PDO;
use PDOException;
```
或者寫 `\PDO`。同樣情況：`\Exception` / `\DateTime` / `\Throwable`。

---

## 🎯 最終驗收：換一行，換咗個底

```php
$repo = new JsonTodoRepository(__DIR__ . '/../todos.json');
// 改成
$repo = new PdoTodoRepository($pdo);
```

**Controller 一個字唔改、routes 一個字唔改、Todo 一個字唔改**
→ 個 app 由檔案儲存變成 MySQL。

呢個就係 Day 1 個 interface 五日之後嘅回報。

---

## 對照 Laravel

| 你今日寫嘅 | Laravel |
|---|---|
| `public/index.php` | `public/index.php` |
| `routes.php` | `routes/web.php` |
| `[TodoController::class, 'index']` | **一模一樣嘅寫法** |
| `new $class($repo)` | Service Container |
| `app/Controllers/` | `app/Http/Controllers/` |
| `autoload.php` | `vendor/autoload.php` |
| Controller 入面 echo HTML（核突） | Blade 存在嘅理由 |
| Router 之前想插共用邏輯 | Middleware 嘅插入點 |

Laravel 真身：
```php
Route::get('/todos', [TodoController::class, 'index']);
```

**同你寫嘅一樣。**

---

## 🧪 測試方法

```bash
cd week7/Day5/public
php -S localhost:8000
```

PHP 內置開發 server，唔使 Apache。

⚠️ `php -S` 特性：真實存在嘅檔案會直接送出，唔入 router。
所以測試要用唔存在嘅路徑（`/todos`）。

---

## 🧭 進度

```
Week 7  [■■■■■□□]  Day 5/7  ← 新內容完成
```

Day 1 ✅ Interface → Day 2 ✅ Autoloading → Day 3 ✅ 練習
→ Day 4 ✅ Namespace → **Day 5 ✅ Router** → Day 6 練習 → Day 7 休息
