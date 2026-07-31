# Week 7 總覽 — 由「一堆 PHP 檔」到「mini Laravel」

> **Week 7 五日，個 app 喺瀏覽器上面完全冇變過。**
> 冇新 feature、冇新畫面。改嘅係 codebase 結構。
> 呢個叫 **Refactoring** —— 真實工作大半時間做緊嘅事。

---

## 🗺️ 最終架構

```
week7/Day5/
├── public/
│   └── index.php                    ← 唯一入口（Front Controller）
├── app/
│   ├── Controllers/
│   │   └── TodoController.php
│   ├── Models/
│   │   ├── Todo.php                 ← domain model
│   │   └── TodoList.php             ← collection
│   └── Repositories/
│       ├── TodoRepositoryInterface.php   ← 合約
│       ├── JsonTodoRepository.php        ← 實作 A
│       └── PdoTodoRepository.php         ← 實作 B
├── autoload.php                     ← PSR-4
├── routes.php                       ← URL 表
└── todos.json
```

---

## 🔄 一個 Request 嘅完整旅程

```
瀏覽器 POST /todos  (title=買嘢)
   │
   ▼
public/index.php                     ← 唯一入口
   │  $_SERVER['REQUEST_METHOD'] = "POST"
   │  $_SERVER['REQUEST_URI']    = "/todos"
   │  → $key = "POST /todos"
   ▼
routes.php 查表
   │  ["App\Controllers\TodoController", "store"]
   ▼
new $class($repo)                    ← 用字串 new + DI
   │  autoloader 出動：App\Controllers\TodoController
   │                 → app/Controllers/TodoController.php
   ▼
TodoController::store()
   │  $title = trim($_POST["title"] ?? "");
   │  $todo = new Todo(null, $title);           ← 製造喺 Controller
   ▼
TodoRepositoryInterface::save()      ← Controller 只認合約
   │
   ▼
JsonTodoRepository::save()           ← 實際做嘢（可以換成 Pdo 版）
   │  id === null → INSERT 分支
   │  生成新 id → setId() 回填
   │  dehydrate → writeAll
   ▼
header("Location: /todos"); exit;    ← PRG
   │
   ▼
瀏覽器 GET /todos  → 再行一次上面流程 → index()
```

**每一層都唔知下一層嘅細節。呢個就係架構。**

---

## 📅 五日各自解決咩問題

### Day 1 — Interface

**問題：** `__construct(private TodoRepository $repo)` 綁死咗一個具體 class。
換儲存方式要改 Controller；想測試 Controller 要起成個 DB。

**解法：** 定義合約，Controller 只認合約。

```php
interface TodoRepositoryInterface
{
    public function findAll(): TodoList;
    public function findById(int $id): ?Todo;
    public function save(Todo $todo): void;
    public function delete(int $id): void;
}
```

**咩唔入 interface：**
- `private hydrate()` — 實作專屬（JSON 版有自己嘅 private method）
- `__construct` — `new` 一定要打具體 class 名，發生喺 polymorphism 之前
- `create()` / `toggle()` — 前者係「製造」，後者係 domain 行為

**⭐ 順帶學到最貴嘅一課：Persist vs Command**

```php
"UPDATE todos SET is_done = NOT is_done"   // ❌ save 兩次 = 翻轉兩次
"UPDATE todos SET is_done = ?"             // ✅ 照抄 object 現時狀態
```

> `save()` = 把 object 現時狀態寫入 DB，唔係執行操作 → **idempotent**

---

### Day 2 — Autoloading

**問題：**
```php
require 'Todo.php';
require 'TodoList.php';
require 'TodoRepositoryInterface.php';
require 'TodoRepository.php';
```
每個入口檔都要抄、加 class 要去所有入口檔加、順序仲要啱。

**解法：** 五行。

```php
spl_autoload_register(function (string $class) {
    $file = __DIR__ . "/" . $class . ".php";
    if (file_exists($file)) {
        require $file;
    }
});
```

**成果：** 加幾多個新 class 都**乜都唔使做**。

---

### Day 3 — 練習日（JsonTodoRepository）

**同一份 interface，用 JSON 檔存資料。**

`test.php` 只改一行，五行輸出一模一樣 —— **而且 MySQL 熄咗都跑得成**。

> 呢日就係 Day 1 個問題「寫個 interface 但 app 一樣，學嚟做咩」嘅答案。

**撞過嘅坑：**
- `json_encode($obj)` 只出 public property → private 全部係 `{}` → 要自己寫 `dehydrate()`
- `max([])` 會 ValueError → 空陣列要 guard
- `created_at` 只改 array 唔改 object → 第二次 save 會抹走佢（**測試唔會發現**）

---

### Day 4 — Namespace / PSR-4

**問題：** 一個 project 唔可以有兩個 `Todo`。裝兩個 package 都有 `Logger` 就死。

**解法：** 畀 class 一個「姓氏」。

```php
namespace App\Models;       // 宣告
use App\Models\Todo;        // 引入（⚠️ 唔係載入）
```

**PSR-4：** `App\Models\Todo` → `app/Models/Todo.php`

**→ 子資料夾終於開得成。** Day 1 叫你唔好開，等嘅就係呢日。

**⚠️ Function 有 fallback，Class 冇：**
```php
date('Y-m-d');    // ✅ 自動去全域搵
new PDO(...);     // ❌ 搵 App\Repositories\PDO → 要 use PDO;
```

---

### Day 5 — Router / Front Controller

**問題：** 一個功能一個檔，重複 code、冇全局視野、加中間層要逐個改。

**解法：** 所有 request 入 `public/index.php`，查表決定去邊。

```php
return [
    "GET /todos"  => [TodoController::class, "index"],
    "POST /todos" => [TodoController::class, "store"],
];
```

**核心三行：**
```php
[$class, $action] = $routes[$key];
$controller = new $class($repo);      // variable class name + DI
$controller->$action();               // variable method name
```

---

## 🎓 Week 7 六個核心概念

| # | 概念 | 一句話 |
|---|---|---|
| 1 | **Interface** | 合約 —— 唔使認識邊個 class，只需知對方識做咩 |
| 2 | **Idempotency** | save 一次同十次結果一樣（Persist ≠ Command） |
| 3 | **Autoloading** | 用到邊個 class 先載邊個檔 |
| 4 | **Namespace** | 畀 class 一個姓氏；PSR-4 令每層 = 一層資料夾 |
| 5 | **Front Controller** | 唯一入口 = 共用邏輯嘅插入點 |
| 6 | **Dependency Injection** | 依賴由外面傳入，唔喺內部 `new` |

---

## 🔀 概念邊界（易撈亂）

| A | B | 分別 |
|---|---|---|
| `extends`（繼承） | `implements`（interface） | **is-a** vs **can-do**；帶實作 vs 只有簽名；一個 vs 無限個 |
| `use` | `require` | **改花名** vs **載入檔案**（`use` 完全唔會開任何檔） |
| Autoload | Routes | **檔案層**（class → 路徑） vs **網絡層**（URL → 功能）—— 完全無關 |
| Model | Repository | **一件嘢本身**（+ 佢自己嘅行為） vs **資料出入口** |
| Domain 行為 | 資料操作 | `$todo->toggle()` vs `$repo->save($todo)` |

**Interface ≈ C++ 純虛基類**（全部 `= 0`、冇 data member），
唔係普通父類繼承。C++ 冇獨立關鍵字所以要用 class 扮。

---

## 🐛 Week 7 撞過嘅坑（完整清單）

### 型別 / 轉換
```php
$pdo->lastInsertId()          // string！要 (int)
(string) false === ''         // ⚠️ 唔係 '0' → bool 落 MySQL 要 (int)
$stmt->fetch() 失敗            // false → 用 === false
json_decode() 失敗             // null → 用 ?? []
```

### 語法
```php
Todo["id"]                                    // ❌ object 用 ->，[] 係 array
__construct(?int $id = null, string $title)   // ❌ 有預設值嘅要排最後
array_values($arr);                           // ❌ 冇接住回傳值
$this->dehydrate($t)["x"] = ...;              // ❌ 唔可以對 temporary 賦值
```

### 架構
```php
if ($_SERVER["REQUEST_METHOD"] === "POST")    // ❌ router 已經分流咗
header("Location: index.php")                 // ❌ 用 URL 唔用檔名
htmlspecialchars($todo->getId())              // ❌ int 唔使 escape
```

### Namespace
```
Undefined type 'App\Repositories\PDO'         // → use PDO;
收到: JsonTodoRepository（冇前綴）              // → 入口檔漏咗 use
$class vs $relative 用錯                       // → 路徑多咗一層 App/
```

---

## 🏗️ 對照真 Laravel

| 你寫嘅 | Laravel | 備註 |
|---|---|---|
| `public/index.php` | `public/index.php` | 一樣 |
| `routes.php` | `routes/web.php` | 一樣概念 |
| `[TodoController::class, 'index']` | 一模一樣 | Laravel 用 `Route::get()` 包住 |
| `new $class($repo)` | Service Container | Laravel 會自動解析所有依賴 |
| `app/Controllers/` | `app/Http/Controllers/` | |
| `autoload.php` | `vendor/autoload.php` + `composer.json` psr-4 | |
| Repository + Model | Eloquent（二合一） | |
| Controller `echo` HTML | Blade | **你話「核突」嗰個位** |
| Router 前想插邏輯 | Middleware | |

> **你今日覺得核突嘅位，就係 Laravel 某個功能存在嘅理由。**
> 呢個對應關係，係你將來學 Laravel 最大嘅優勢。

---

## ⚠️ 關於「記住框架，細節用 AI 填」

**啱嘅部分：**
框架比細節值錢。`htmlspecialchars` 點串、`array_column` 收咩參數 —— 永遠查得返。
但「Controller 唔應該知道底層係 MySQL」呢個判斷，**唔知道就永遠唔會去查**。

**但要記住：**
你今日撞嘅兩個 bug —— `save()` 非幂等、`created_at` 被抹走 ——
**AI 生成嘅 code 一樣會有，而且測試會過。**

- Week 1-6 令你**睇得明** AI 寫嘅嘢
- Week 7 令你**知道要叫佢寫咩**
- 親手撞過嘅坑令你**發現得到佢寫錯咩**

三樣都要。

---

## 🔜 Week 8：Integration Week

- 唔學新嘢
- 用 Week 5-7 全套做一個綜合 project
- 直接用呢個結構開檔 + 加返 Week 5 嘅登入系統

**預告一個你一定會撞到嘅問題：**

```sql
ALTER TABLE todos ADD COLUMN user_id INT UNSIGNED NOT NULL;
```
↑ 呢句會失敗。舊有 row 冇 `user_id`，MySQL 要填咩？NOT NULL 又唔准 NULL。

**呢個叫 schema migration —— Laravel 成套 Migration 系統就係為咗解決佢。**
到時再拆。撞完你就永世唔會忘記點解要有 migration。
