# Week 7 Day 4 總結 — Namespace / PSR-4

**日期：** 2026-07-31
**主題：** Namespace、`use`、PSR-4 autoloading
**產出：** `month2/week7/Day4/` — 結構重組成 `app/` 分層

---

## 今日一句話

> **Namespace = 畀 class 一個「姓氏」，令唔同資料夾嘅同名 class 唔會撞。**

---

## 解決咗咩問題

**之前：** 所有 class 扁平擺一齊，靠改名避開衝突。

Day 1 你就撞過一次 —— interface 同實作都想叫 `TodoRepository`，唯有加 `Interface` suffix。

**Week 8 會更嚴重：**
```
Todo   ← domain model
Todo   ← DB entity
Todo   ← API resource
```
三個都叫 Todo，PHP 唔准 → 逼你改成 `TodoModel` / `TodoEntity` / `TodoResource`。

**最實際嘅情況：** 裝兩個第三方 package，兩個都有 `Logger` —— 你改唔到人哋嘅 code。

**之後：**
```
App\Models\Todo
App\Api\Todo        ← 共存，冇問題
```

---

## 新結構

```
week7/Day4/
├── autoload.php
├── test.php
├── todos.json
└── app/
    ├── Models/
    │   ├── Todo.php                     App\Models\Todo
    │   └── TodoList.php                 App\Models\TodoList
    └── Repositories/
        ├── TodoRepositoryInterface.php  App\Repositories\TodoRepositoryInterface
        └── JsonTodoRepository.php       App\Repositories\JsonTodoRepository
```

**Day 1 我叫你唔好開子資料夾 —— 等嘅就係今日。**
冇 namespace，子資料夾要手寫一堆 `require '../Models/Todo.php'`；
有咗 PSR-4，資料夾結構自動由 namespace 推算出嚟。

---

## 三個關鍵字

### `namespace` — 宣告歸屬

```php
<?php

namespace App\Models;       // 必須係 <?php 之後第一句

class Todo
{
}
```

### `use` — 引入（⚠️ 唔係載入）

```php
namespace App\Repositories;

use App\Models\Todo;        // 「我以下寫 Todo，即係 App\Models\Todo」

class JsonTodoRepository
{
    public function findById(int $id): ?Todo { }
}
```

> **`use` 唔會載入任何檔案。佢淨係改花名。**
> 載入係 autoloader 嘅事。呢個係初學者最大誤解。

### FQCN — 全名

```php
App\Models\Todo             // Fully Qualified Class Name
```

唔想寫 `use` 就每次打全名：`new App\Models\Todo(...)`

---

## PSR-4：namespace ↔ 資料夾

```
App\Models\Todo                      →  app/Models/Todo.php
App\Repositories\JsonTodoRepository  →  app/Repositories/JsonTodoRepository.php
```

**Namespace 每一層 = 一層資料夾。** 有咗呢條約定，autoloader 就推算得出路徑。

---

## 今日寫嘅 autoloader

```php
<?php

spl_autoload_register(function (string $class) {
    $prefix = "App\\";
    $baseDir = __DIR__ . "/app/";

    // 唔係 App\ 開頭 → 唔關我事，交畀下一個 autoloader
    if (str_starts_with($class, $prefix) === false) {
        return;
    }

    $relative = substr($class, strlen($prefix));
    $file = $baseDir . str_replace("\\", "/", $relative) . ".php";

    if (file_exists($file)) {
        require $file;
    }
});
```

### 四步轉換

```
"App\Repositories\JsonTodoRepository"
    ↓ str_starts_with 檢查前綴
    ↓ substr 掉走 "App\"
"Repositories\JsonTodoRepository"
    ↓ str_replace "\" → "/"
"Repositories/JsonTodoRepository"
    ↓ 拼 baseDir + ".php"
__DIR__ . "/app/Repositories/JsonTodoRepository.php"
```

### 兩個 guard，兩個唔同理由

| Guard | 作用 |
|---|---|
| `str_starts_with($class, $prefix)` | 唔係我負責嘅 namespace → 交畀 Composer 等其他 autoloader |
| `file_exists($file)` | 檔案唔存在 → 靜靜 return，錯誤訊息先會準確講「class not found」 |

---

## 🐛 今日撞過嘅坑

### ① 算咗 `$relative` 但用返 `$class`

```php
$relative = substr($class, strlen($prefix));
$file = $baseDir . str_replace("\\", "/", $class) . ".php";
//                                        ^^^^^^ 應該係 $relative
```
結果路徑變 `app/App/Repositories/...`，多咗一層。

### ② `test.php` 冇 `use` → PHP 搵全域 class

Debug 輸出顯示：
```
收到: JsonTodoRepository        ← 冇 App\ 前綴
```

原因：`test.php` 冇 namespace，`new JsonTodoRepository(...)` 被當成全域 class。

**修正：**
```php
use App\Models\Todo;
use App\Repositories\JsonTodoRepository;
```

### ③ 字串入面嘅反斜線

```php
"App\"     // ❌ \" 被當成跳脫
"App\\"    // ✅
```

---

## ⭐ Debug 輸出揭示嘅兩件事

```
收到: App\Repositories\JsonTodoRepository      ← new 個 repo
收到: App\Repositories\TodoRepositoryInterface ← 因為 implements，PHP 即刻要驗證
收到: App\Models\Todo                          ← new Todo
NULL  int(1)
收到: App\Models\TodoList                      ← 到 findAll() 先至載入！
bool(true) bool(true) NULL
```

**① Lazy loading 嘅實證**
`TodoList` 到第 5 行測試先載入 —— 前面四個 `var_dump` 期間，個檔案完全冇被打開過。

**② Interface 由 PHP 強制執行嘅時刻**
`new JsonTodoRepository` → 讀 class → 見到 `implements` → **即刻載入 interface 驗證有冇實作齊**。

---

## ⚠️ Function 有 fallback，Class 冇

```php
namespace App\Repositories;

date('Y-m-d');        // ✅ 搵唔到 App\Repositories\date → 自動 fallback 去全域
json_encode($x);      // ✅ 同上
file_exists($f);      // ✅ 同上

new PDO(...);         // ❌ 搵 App\Repositories\PDO → 冇 fallback → Fatal error
```

**Class 一定要 `use PDO;` 或者寫 `\PDO`。**

呢個係 namespace 最常撞嘅坑。今日用 JSON 版所以冇遇到，Day 5 接返 PDO 就會撞。

同理：`\Exception`、`\DateTime`、`\ArrayObject` 全部要處理。

---

## ✅ 驗證結果

```
NULL  int(1)  bool(true)  bool(true)  NULL
```

同 Day 1（PDO 扁平）、Day 3（JSON 扁平）一模一樣。

**Week 7 到今日為止，個 app 行為由頭到尾零改變，但 codebase 已經完全唔同。**

---

## 🧭 進度

```
Week 7  [■■■■□□□]  Day 4/7
```

Day 1 ✅ Interface → Day 2 ✅ Autoloading → Day 3 ✅ 練習（JsonTodoRepository）
→ **Day 4 ✅ Namespace** → Day 5 Router → Day 6 練習 → Day 7 休息

---

## 🔜 Day 5：Router（Week 7 最後一日新內容）

**問題：** 真實 web app 會變成
```
todo_add.php
todo_delete.php
todo_toggle.php
todo_list.php
```
每個功能一個檔，散晒。

**解法：** 所有 request 入 `public/index.php`，查一張路由表決定去邊。

```php
$router->get('/todos', [TodoController::class, 'index']);
$router->post('/todos', [TodoController::class, 'store']);
$router->post('/todos/delete', [TodoController::class, 'destroy']);
```

**一張表列晒成個 app 有咩 URL。呢個就係 Laravel `routes/web.php`。**
