# My PHP Cheatsheet

> 我常用嘅 code snippets。累 / 趕時間就 copy。  
> 每次遇到「Google 過類似嘢」就順手加落嚟。

---

## 目錄

### 基礎（Week 5）
1. [PDO 連線](#1-pdo-連線)
2. [Prepared Statement - SELECT](#2-prepared-statement---select)
3. [Prepared Statement - INSERT / UPDATE / DELETE](#3-prepared-statement---insert--update--delete)
4. [Session Start + Flash Message](#4-session-start--flash-message)
5. [Password Hash + Verify](#5-password-hash--verify)
6. [PRG Pattern (Post-Redirect-Get)](#6-prg-pattern-post-redirect-get)
7. [XSS Escape (htmlspecialchars)](#7-xss-escape-htmlspecialchars)

### OOP（Week 6）
8. [OOP Class Template](#8-oop-class-template)
9. [Collection Class Template](#9-collection-class-template)

### 架構（Week 7）
10. [Repository Pattern（完整版）](#10-repository-pattern完整版)
11. [Repository Interface — 咩入去咩唔入去](#11-repository-interface--咩入去咩唔入去)
12. [Upsert `save()`](#12-upsert-save)
13. [Hydration / Dehydration](#13-hydration--dehydration)
14. [Autoloading](#14-autoloading)
15. [JSON 檔案儲存（Repository 實作）](#15-json-檔案儲存repository-實作)
16. [Namespace + PSR-4](#16-namespace--psr-4)
17. [PSR-4 Autoloader](#17-psr-4-autoloader)

### 速查表
- [PDO 錯誤碼](#-pdo-錯誤碼速查)
- [PHP 型別轉換陷阱](#-php-型別轉換陷阱)
- [Array function：改嘢 vs 計嘢](#-array-function改嘢-vs-計嘢)
- [PSR-12 格式](#-psr-12-快查)
- [Debug: Class not found](#-debug-class-xxx-not-found)
- [雜項](#-雜項)

---

## 1. PDO 連線
**幾時用：** 每個 PHP 檔案要連 DB 前

```php
<?php
try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=YOUR_DB;charset=utf8mb4",
        "root",
        "",
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    die ("DB Connection failed: " . $e->getMessage());
}
```

**常見坑：**
- `?>` 唔好加喺 db.php 尾（避免意外 output）
- Charset 一定用 `utf8mb4`（唔用 `utf8`）—— `utf8` 係 MySQL 殘廢版，存唔到 emoji
- ⚠️ 喺有 namespace 嘅檔案入面，要 `use PDO;` `use PDOException;`，或者寫 `new \PDO(...)`

---

## 2. Prepared Statement - SELECT
**幾時用：** 用戶輸入嚟 query DB（永遠用 prepared statement 防 SQL Injection）

```php
// 單 row (return array 或 false)
$stmt = $pdo->prepare("SELECT id, username FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

// 多 rows (return array of arrays)
$stmt = $pdo->prepare("SELECT id, username FROM users WHERE role = ?");
$stmt->execute([$role]);
$rows = $stmt->fetchAll();
```

**常見坑：**
- `?` 唔好加引號（`WHERE id = '?'` 錯）
- 唔好用 `SELECT *`（明確列出欄位）
- `fetch()` 失敗 return `false`，記得 check
- Fetch 出嚟 `id` 係 string 唔係 int（需要嘅話用 `(int)`）

---

## 3. Prepared Statement - INSERT / UPDATE / DELETE
**幾時用：** 寫入 DB 操作（永遠用 prepared statement）

```php
// INSERT
$stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
$stmt->execute([$username, $hashedPassword, "user"]);
$newId = $pdo->lastInsertId();   // 攞新 row 個 id

// UPDATE
$stmt = $pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
$stmt->execute([$newRole, $id]);
$affected = $stmt->rowCount();   // 有幾多 row 被影響

// DELETE
$stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
$stmt->execute([$id]);
```

**常見坑：**
- UPDATE / DELETE **一定**要有 `WHERE`（冇 WHERE = 影響成 table）
- 執行前先 SELECT 同一個 WHERE 驗證
- Duplicate INSERT 會拋 exception（SQLSTATE 23000）
- `lastInsertId()` 要喺 execute 之後即刻 call

---

## 4. Session Start + Flash Message
**幾時用：** 用戶登入狀態、跨頁訊息（例如 "Added successfully"）

```php
// 每個用 session 嘅檔案最頂（通常放 db.php）
session_start();

// Flash message helpers (OOP 版)
class Flash {
    public function has($type) {
        return isset($_SESSION["flash"][$type]);
    }
    
    public function set($type, $msg) {
        $_SESSION["flash"][$type] = $msg;
    }
    
    public function get($type) {
        if (!$this->has($type)) return null;
        $msg = $_SESSION["flash"][$type];
        unset($_SESSION["flash"][$type]);
        return $msg;
    }
}

// 用法
$flash = new Flash();
$flash->set("success", "Added successfully");

// 之後 redirect 到下一頁
// 下一頁：
$msg = $flash->get("success");  // 讀完自動 unset
```

**常見坑：**
- `session_start()` 前**唔可以** output 任何嘢（包括空格 / `<?php` 前嘅字元）
- Flash `get()` 讀完會 unset，refresh 之後訊息消失（正常）
- 要配合 PRG pattern 用（見 Entry 6）

---

## 5. Password Hash + Verify
**幾時用：** 用戶註冊（hash）+ 用戶登入（verify）

```php
// 註冊 - hash 之後存 DB
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
$stmt->execute([$username, $hashedPassword]);

// 登入 - verify
$stmt = $pdo->prepare("SELECT id, password FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user["password"])) {
    // 登入失敗（統一訊息，防 user enumeration）
    $flash->set("error", "Invalid username or password");
    header("Location: login.php");
    exit;
}

// 登入成功
session_regenerate_id(true);   // 防 session fixation
$_SESSION["user_id"] = $user["id"];
```

**常見坑：**
- 用 `PASSWORD_DEFAULT` 而唔係 `PASSWORD_BCRYPT`（未來自動升級算法）
- Password column 用 `VARCHAR(255)`（hash 長度 60，預留空間）
- Login fail 訊息**唔可以**話「username 錯」或「password 錯」，統一 "Invalid ..."
- 登入成功後**一定**要 `session_regenerate_id(true)` 防 fixation

---

## 6. PRG Pattern (Post-Redirect-Get)
**幾時用：** 任何 POST 操作之後（防 refresh 重複提交）

```php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // 1. 處理 POST（INSERT / UPDATE / DELETE）
    $stmt = $pdo->prepare("INSERT INTO todos (title) VALUES (?)");
    $stmt->execute([$title]);
    
    // 2. 設 flash message
    $flash->set("success", "Added: $title");
    
    // 3. Redirect + exit（呢兩句係命根）
    header("Location: todo.php");
    exit;
}

// GET request 先繼續 render
$stmt = $pdo->prepare("SELECT ...");
$stmt->execute();
$todos = $stmt->fetchAll();
```

**常見坑：**
- **一定要 `exit;`**（`header()` 唔會停 script）
- `header()` 前**唔可以** output 任何嘢
- Empty validation error 可以唔 redirect（保留用戶輸入方便修改）
- Refresh 之後見到 form resubmit dialog = 冇做 PRG

---

## 7. XSS Escape (htmlspecialchars)
**幾時用：** 任何 render 外部 data（DB / 用戶輸入 / API）到 HTML 時

```php
// 基本用法
<?= htmlspecialchars($user["username"]) ?>

// 喺 attribute 入面（例如 value）
<input type="text" value="<?= htmlspecialchars($title) ?>">

// Object property
<?= htmlspecialchars($user->username) ?>

// Loop
<?php foreach ($todos as $todo): ?>
    <td><?= htmlspecialchars($todo->title) ?></td>
<?php endforeach; ?>
```

**規則：**
- ✅ String from DB / user input → escape
- ✅ String from API → escape  
- ❌ Int / bool → 唔使
- ❌ Hardcode string（例如 "✅ Done"）→ 唔使

**常見坑：**
- 一漏一律出事（一致性要求 100%）
- Hardcoded test data 冇 malicious input 睇唔到 bug
- 攻擊者可以喺 DB 存 `<script>alert(1)</script>` 做 stored XSS
- 對於 JSON output 用 `json_encode()`（自動 escape）而唔係 htmlspecialchars

---

## 8. OOP Class Template
**幾時用：** 建立新 domain class（例如 User / Todo / Product）

```php
<?php

namespace App\Models;

class Todo
{
    public function __construct(
        private ?int $id,                    // null = 未存過 DB
        private string $title,
        private bool $done = false,
        private ?string $createdAt = null,
    ) {}

    // ---- Domain 行為（排前面，最有資訊量）----

    public function toggle(): void
    {
        $this->done = !$this->done;
    }

    // ---- Setter（只開需要嘅，畀 repository 回填）----

    public function setId(int $id): void     // 收 int 唔收 ?int：只會由「冇」變「有」
    {
        $this->id = $id;
    }

    // ---- Getters ----

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function isDone(): bool           // bool getter 用 is 開頭
    {
        return $this->done;
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt;
    }
}
```

```php
// 用法
$todo = new Todo(null, "Buy milk");      // 新嘅，id 係 null
$todo->toggle();
echo $todo->isDone() ? "Done" : "Pending";
```

**要點：**
- `?int $id` — 新 object 未有 id，用 `null` 唔用 `0` / `-1`（magic number）
- Property 一律 `private` + getter，唔好 `public`（封裝）
- **唔好預先開一堆用唔著嘅 setter** — 每個 public method 都係一個對外承諾
- Method 順序：行為 → setter → getter（讀者最想知「識做咩」）
- ⚠️ **有預設值嘅參數必須排最後**
  ```php
  __construct(?int $id = null, string $title)   // ❌ Deprecated，= null 會被無視
  ```
  想 `new Todo("買嘢")` 咁短？用 PHP 8 **Named Arguments**：
  ```php
  new Todo(id: null, title: "買嘢");
  ```

---

## 9. Collection Class Template
**幾時用：** 管理一堆 domain object（例如 TodoList / UserList）

```php
<?php

namespace App\Models;

class TodoList
{
    public function __construct(
        private array $todos = [],
    ) {}

    public function add(Todo $todo): void
    {
        $this->todos[] = $todo;
    }

    public function find(int $id): ?Todo
    {
        foreach ($this->todos as $todo) {
            if ($todo->getId() === $id) {
                return $todo;
            }
        }
        return null;
    }

    public function remove(int $id): bool
    {
        foreach ($this->todos as $index => $todo) {
            if ($todo->getId() === $id) {
                unset($this->todos[$index]);
                $this->todos = array_values($this->todos);   // ⚠️ 重排 index
                return true;
            }
        }
        return false;
    }

    public function all(): array
    {
        return $this->todos;
    }

    public function count(): int
    {
        return count($this->todos);
    }

    public function countDone(): int
    {
        return count(array_filter($this->todos, fn($t) => $t->isDone()));
    }

    public function countPending(): int
    {
        return $this->count() - $this->countDone();   // Single Source of Truth
    }
}
```

**要點：**
- `unset()` 之後**一定要 `array_values()`** 重排 index
  → 唔重排，`json_encode` 會出 `{"0":..,"2":..}`（object）而唔係 `[...]`（array），AJAX 前端會爆
- `countPending()` 用恆等式 `count() - countDone()`，唔好獨立再數一次
- 唔好將 `$todos` 開做 `public` — 外面直接改就冇曬保護

### ⭐ 進階：implement PHP 內置 interface

| Interface | 要求實作 | 得到 |
|---|---|---|
| `Countable` | `count(): int` | `count($list)` 直接用 |
| `IteratorAggregate` | `getIterator(): Traversable` | `foreach ($list as $t)` |
| `ArrayAccess` | `offsetGet/Set/Exists/Unset` | `$list[0]` |
| `JsonSerializable` | `jsonSerialize(): mixed` | `json_encode($list)` |
| `Stringable` | `__toString(): string` | `echo $list` |

Laravel `Collection` 就係 implement 曬呢堆，所以用落好似原生 array。

> **Interface 唔止你自己寫嘅 —— PHP 同 framework 都定義咗一堆等你 implement。**

---

## 10. Repository Pattern（完整版）

**幾時用：** 想將 DB 操作抽出去，令 UI code 唔關心 SQL

### 1. 先定 Interface（合約）

```php
<?php

namespace App\Repositories;

use App\Models\Todo;
use App\Models\TodoList;

interface TodoRepositoryInterface
{
    public function findAll(): TodoList;

    public function findById(int $id): ?Todo;

    public function save(Todo $todo): void;

    public function delete(int $id): void;
}
```

**只有四個 method（CRUD）。**

| 唔應該放入去 | 原因 |
|---|---|
| `create(string $title)` | 「製造」唔係「儲存」。喺 Controller 寫 `new Todo(null, $title)` |
| `toggle(int $id)` | Domain 行為，屬於 `Todo::toggle()` |
| `private hydrate()` | 實作細節，JSON 版有自己嘅私有 method |
| `__construct` | `new` 要打具體 class 名，發生喺 polymorphism 之前 |

---

### 2. PDO 實作

```php
<?php

namespace App\Repositories;

use App\Models\Todo;
use App\Models\TodoList;
use PDO;                            // ⚠️ 全域 class 要 use

class PdoTodoRepository implements TodoRepositoryInterface
{
    public function __construct(
        private PDO $pdo,           // DI：傳入，唔好內部 new PDO()
    ) {}

    public function findAll(): TodoList
    {
        $stmt = $this->pdo->prepare("SELECT id, title, is_done, created_at FROM todos");
        $stmt->execute();

        $list = new TodoList();
        foreach ($stmt->fetchAll() as $row) {
            $list->add($this->hydrate($row));
        }

        return $list;
    }

    public function findById(int $id): ?Todo
    {
        $stmt = $this->pdo->prepare(
            "SELECT id, title, is_done, created_at FROM todos WHERE id = ?"
        );
        $stmt->execute([$id]);
        $row = $stmt->fetch();

        if ($row === false) {       // ✅ 唔用 truthy check
            return null;
        }

        return $this->hydrate($row);
    }

    public function save(Todo $todo): void
    {
        if ($todo->getId() === null) {
            $stmt = $this->pdo->prepare("INSERT INTO todos (title) VALUES (?)");
            $stmt->execute([$todo->getTitle()]);
            $todo->setId((int) $this->pdo->lastInsertId());   // 回填，唔使再 query
            return;                                           // early return
        }

        $stmt = $this->pdo->prepare(
            "UPDATE todos SET title = ?, is_done = ? WHERE id = ?"
        );
        $stmt->execute([
            $todo->getTitle(),
            (int) $todo->isDone(),  // ⚠️ bool 要 cast
            $todo->getId(),
        ]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM todos WHERE id = ?");
        $stmt->execute([$id]);
    }

    private function hydrate(array $row): Todo
    {
        return new Todo(
            (int) $row["id"],
            $row["title"],
            (bool) $row["is_done"],
            $row["created_at"],
        );
    }
}
```

---

### 3. 用法

```php
<?php

require __DIR__ . '/autoload.php';

use App\Models\Todo;
use App\Repositories\PdoTodoRepository;

$repo = new PdoTodoRepository($pdo);        // DI

// 讀
$list = $repo->findAll();
$todo = $repo->findById(1);

// 新增
$todo = new Todo(null, "Buy milk");         // Controller 負責製造
$repo->save($todo);
echo $todo->getId();                        // 已回填

// Toggle
$todo = $repo->findById(1);
$todo->toggle();                            // domain 層改狀態
$repo->save($todo);                         // repository 只負責寫低

// 刪
$repo->delete(1);
```

**Toggle 三步曲：`findById` → `toggle` → `save`**
唔好喺 repository 寫 `SET is_done = NOT is_done`。

---

### ⚠️ Repository 常見坑

| 坑 | 正確做法 |
|---|---|
| 內部 `new PDO()` | Constructor DI 傳入 |
| `SET is_done = NOT is_done` | `SET is_done = ?`，值由 object 攞 |
| `create()` 內部 call `findById` | `setId((int) lastInsertId())` 回填，慳一次 query |
| `lastInsertId()` 唔 cast | 佢回傳 **string**，strict_types 會爆 |
| bool 直接落 DB | `(int) $todo->isDone()` —— `(string) false` 係 `''` 唔係 `'0'` |
| `return $row ? ... : null` | `if ($row === false) { return null; }` |
| 加新 column 只改一個 SELECT | **同時改晒** 所有 SELECT + `hydrate()` |
| Repository 做 domain logic | 只做 data access；行為放 domain model |

---

### 🔑 核心原則：save() 要幂等

**`save()` = 把 object 現時狀態寫入 DB，唔係執行操作。**

所以佢係 **idempotent**：

```php
$repo->save($todo);
$repo->save($todo);
$repo->save($todo);
// 三次結果一樣
```

測試方法：
```php
$before = $repo->findById($id)->isDone();
$repo->save($todo);
$repo->save($todo);
var_dump($before === $repo->findById($id)->isDone());   // 必須 true
```

---

## 11. Repository Interface — 咩入去咩唔入去
```php
<?php

interface TodoRepositoryInterface
{
    public function findAll(): TodoList;
    public function findById(int $id): ?Todo;
    public function save(Todo $todo): void;
    public function delete(int $id): void;
}
```
### 咩入去、咩唔入去

| | 入 interface？ | 原因 |
|---|---|---|
| public 業務 method | ✅ | 對外承諾，任何實作都要有 |
| `private hydrate()` | ❌ | 實作專屬。PDO 版要 hydrate，JSON 版要 loadFile —— 概念上唔可能寫入同一份合約 |
| `__construct` | ❌ | `new` 一定要打具體 class 名，發生喺 polymorphism 開始之前 |
| property | ❌ | 語法唔准（只准 `const`） |

### 命名兩派

```
A 派（Symfony）：TodoRepositoryInterface  +  TodoRepository
B 派（Java/.NET）：TodoRepository  +  PdoTodoRepository / JsonTodoRepository
```
多過一個實作時，B 派對稱啲。

### 用嘅時候

```php
// Controller 只認 interface，唔知道有 PDO 呢回事
public function __construct(
    private TodoRepositoryInterface $repo
) {}
```

換實作只需改 `new` 嗰一行，Controller 一個字唔使動。

---

## 12. Upsert `save()`
```php
public function save(Todo $todo): void
{
    if ($todo->getId() === null) {
        $stmt = $this->pdo->prepare("INSERT INTO todos (title) VALUES (?)");
        $stmt->execute([$todo->getTitle()]);
        $todo->setId((int) $this->pdo->lastInsertId());   // ← 回填
        return;                                           // ← early return，唔好 else
    }

    $stmt = $this->pdo->prepare("UPDATE todos SET title = ?, is_done = ? WHERE id = ?");
    $stmt->execute([
        $todo->getTitle(),
        (int) $todo->isDone(),
        $todo->getId(),
    ]);
}
```

### ⚠️ 三個坑

**① `lastInsertId()` 回傳 string**
```php
$todo->setId((int) $this->pdo->lastInsertId());
```
唔 cast，一開 `declare(strict_types=1)` 即刻 TypeError。

**② bool 落 MySQL 要 `(int)`**
```php
(int) $todo->isDone()
```
PDO 綁值會轉字串，而 `(string) false === ''`（空字串，**唔係 `'0'`**）。
MySQL STRICT mode 會直接報 `Incorrect integer value: ''`。
→ 本機寬鬆行到、production 爆炸嘅經典 bug。

**③ 唔好用 `SET is_done = NOT is_done`**
```php
// ❌ 破壞 idempotency
"UPDATE todos SET is_done = NOT is_done WHERE id = ?"

// ✅ 照抄 object 現時狀態
"UPDATE todos SET title = ?, is_done = ? WHERE id = ?"
```

### Persist vs Command

| | 思維 | 結果 |
|---|---|---|
| Command | 「幫我反轉佢」→ DB 自己計 | save 兩次 = 翻轉兩次 ❌ |
| **Persist** | 「object 而家係咁，寫落去」 | save 十次結果一樣 ✅ |

`save()` 嘅責任係**把 object 現時狀態寫入 DB**，唔係執行操作。
狀態改變喺 domain 層做：`$todo->toggle();` 然後 `$repo->save($todo);`

### Idempotency test（每次寫 save/PUT/DELETE 都應該跑）

```php
$before = $repo->findById($id)->isDone();
$repo->save($todo);
$repo->save($todo);
$repo->save($todo);
var_dump($before === $repo->findById($id)->isDone());   // 必須 true
```

---

## 13. Hydration / Dehydration

```php
private function hydrate(array $row): Todo
{
    return new Todo(
        (int) $row["id"],
        $row["title"],
        (bool) $row["is_done"],
        $row["created_at"],
    );
}
```

DB row（array）→ Domain object。
`findAll()` 同 `findById()` 共用，DRY。**一定係 private**（實作細節）。

配合 guard clause：
```php
$row = $stmt->fetch();
if ($row === false) {
    return null;
}
return $this->hydrate($row);
```

---

## 14. Autoloading
### 完整寫法

```php
<?php
// autoload.php — 放喺 project 根目錄

spl_autoload_register(function (string $class) {
    $file = __DIR__ . "/" . $class . ".php";

    if (file_exists($file)) {
        require $file;
    }
});
```

### 用法

```php
// 入口檔（index.php / test.php / 任何 entry point）
require __DIR__ . '/autoload.php';

// 之後直接用，唔使 require 任何 class
$repo = new TodoRepository($pdo);
```

---

### ⚠️ 三條硬規則

**① 檔名必須 = class 名**
```
class Todo                         → Todo.php
interface TodoRepositoryInterface  → TodoRepositoryInterface.php
```
autoloader 就係靠呢個約定推算路徑。大小寫都要一致（Linux 分大小寫，Windows 唔分 → 本機行到、上 server 爆）。

**② 一定要 `__DIR__`，唔准用相對路徑**
```php
require "Todo.php";              // ❌ 相對於「執行入口」
require __DIR__ . "/Todo.php";   // ✅ 相對於「呢個檔案自己」
```
驗證：`cd .. && php Day2/test.php` —— 相對路徑會爆。

**③ 搵唔到要靜靜 return，唔好 error**
```php
if (file_exists($file)) {        // ← 冇呢個 guard 就出事
    require $file;
}
```
原因：
- 錯誤訊息會變成「檔案唔存在」而唔係「class 唔存在」（誤導）
- PHP 會逐個試登記咗嘅 autoloader。你一 fatal error，**後面嘅冇機會試** → Composer 第三方 library 全部載唔到

---

### 其他檔案唔准再有 `require`

```php
// TodoList.php 頂部
require_once "Todo.php";    // ❌ 刪咗佢
```

三個理由：
1. **Single Source of Truth** — 兩套載入機制並存，問「呢個 class 邊度載」答唔到
2. **失去 lazy loading** — 用 TodoList 就強制拉埋 Todo，就算冇用到
3. **相對路徑計時炸彈** — 換個目錄跑就爆

**載入呢件事，只由 autoloader 一處負責。**

---

### `require` vs `require_once`

Autoloader 入面**兩個都得**（同一 class 唔會 call 兩次）。
慣例寫 `require` —— `_once` 要維護已載入清單，喺呢個場景係多餘 overhead。

---

### PHP 搵 class 嘅流程

```
new Todo(...)
   ↓
1. Todo 載咗未？ → 載咗 → 用
   ↓ 未載
2. 唔即刻 error，call 登記咗嘅 autoloader（可以有多個，逐個試）
   ↓
3. 有人成功 require → 當冇事，繼續行
   全部搵唔到 → Fatal error: Class "Todo" not found
```

---

### 同 Composer 嘅關係

```php
require __DIR__ . '/vendor/autoload.php';   // Laravel 每個 project 都有呢行
```

本質同你五行一樣，**分別只在「class 名 → 路徑」嘅推算規則：**

| | 規則 | 例 |
|---|---|---|
| 手寫版 | 加 `.php` | `Todo` → `Todo.php` |
| **PSR-4** | namespace 對應資料夾 | `App\Models\Todo` → `app/Models/Todo.php` |

---

### PSR-4 版本

加咗 namespace 之後，見 [17. PSR-4 Autoloader](#17-psr-4-autoloader)。

---

## 15. JSON 檔案儲存（Repository 實作）

```php
private function readAll(): array
{
    if (file_exists($this->filePath)) {
        $json = file_get_contents($this->filePath);
        return json_decode($json, true) ?? [];    // 壞檔 → 空清單
    }
    return [];                                    // 冇檔 → 空清單
}

private function writeAll(array $rows): void
{
    $json = json_encode($rows, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    file_put_contents($this->filePath, $json);
}
```

### ⚠️ 四個必記

**① `json_decode($str, true)` — 第二個參數**
唔傳 `true` 出嚟係 `stdClass` object，唔係 array。

**② 兩個 flag**
| Flag | 作用 |
|---|---|
| `JSON_UNESCAPED_UNICODE` | 中文唔會變 `\u6e2c\u8a66` |
| `JSON_PRETTY_PRINT` | 自動縮排，人手 debug 睇得明 |

多個 flag 用 `|` 連（bitwise OR）。

**③ `json_encode` 只輸出 public property**
```php
class Todo { private $id; private $title; }
json_encode($todo);      // → {}  空對象！
```
→ 所以要自己寫 `dehydrate()`。

**④ Return `[]` 唔好 return `null`**
```php
private function readAll(): ?array   // ❌ 逼每個 caller 寫 null 檢查
private function readAll(): array    // ✅ 冇資料 = 空清單
```

---

### Hydrate / Dehydrate 一對

```php
// array → object
private function hydrate(array $row): Todo
{
    return new Todo(
        (int) $row["id"],
        $row["title"],
        (bool) $row["is_done"],
        $row["created_at"],
    );
}

// object → array（JSON 版專有，PDO 版唔使因為 SQL 直接用 getter）
private function dehydrate(Todo $todo): array
{
    return [
        "id" => $todo->getId(),
        "title" => $todo->getTitle(),
        "is_done" => $todo->isDone(),
        "created_at" => $todo->getCreatedAt(),
    ];
}
```

Key 名用返 DB 欄位名（`is_done` / `created_at`）→ hydrate 兩個實作共用同一套。

---

### JSON 版 `save()` 要自己做 AUTO_INCREMENT

```php
$ids = array_column($rows, "id");
$newId = count($ids) ? max($ids) + 1 : 1;    // ⚠️ max([]) 會 ValueError
```

- `array_column($rows, "id")` — 抽出所有 row 嘅某個欄位
- 用 `max()` 唔用 `end()` — 唔依賴陣列順序，手改過檔案都安全
- **空陣列一定要 guard**，`max([])` 直接爆

### ⚠️ `created_at` 陷阱（違反 idempotency）

MySQL 有 `DEFAULT CURRENT_TIMESTAMP`，JSON 冇 → 要自己補。

```php
// INSERT 分支：改 object，唔好改 array
if ($todo->getCreatedAt() === null) {
    $todo->setCreatedAt(date("Y-m-d H:i:s"));
}
$rows[] = $this->dehydrate($todo);       // dehydrate 之後就有值
```

**所有對 object 嘅修改都要喺 `dehydrate()` 之前完成。**
（`setId()` 同樣道理）

只改 array 唔改 object → 第二次 `save()` 會用 object 入面嘅 `null` 覆蓋返，
**created_at 靜靜地被抹走，而測試唔會發現。**

---

## 16. Namespace + PSR-4

### 三個關鍵字

```php
<?php

namespace App\Models;        // 宣告，必須係 <?php 後第一句

class Todo { }
```

```php
<?php

namespace App\Repositories;

use App\Models\Todo;         // 引入 = 改花名
use PDO;                     // ⚠️ 全域 class 都要 use

class TodoRepository { }
```

### ⚠️ `use` ≠ `require`

> **`use` 唔會載入任何檔案，佢淨係話畀 PHP 聽「我寫 Todo 即係 App\Models\Todo」。**
> 載入係 autoloader 嘅事。

### ⚠️ Function 有 fallback，Class 冇

```php
namespace App\Repositories;

date('Y-m-d');       // ✅ 搵唔到就自動去全域搵
json_encode($x);     // ✅
count($arr);         // ✅

new PDO(...);        // ❌ 搵 App\Repositories\PDO → Fatal error
new DateTime();      // ❌
throw new Exception; // ❌
```

**修正：** `use PDO;` 或者 `new \PDO(...)`（前面加反斜線 = 全域）

呢個係 namespace 最常撞嘅坑。

---

## 17. PSR-4 Autoloader

```php
<?php
// autoload.php — project 根目錄

spl_autoload_register(function (string $class) {
    $prefix = "App\\";                  // ⚠️ 兩條斜線（跳脫）
    $baseDir = __DIR__ . "/app/";

    // Guard 1：唔係我負責嘅 namespace → 交畀下一個 autoloader
    if (str_starts_with($class, $prefix) === false) {
        return;
    }

    $relative = substr($class, strlen($prefix));
    $file = $baseDir . str_replace("\\", "/", $relative) . ".php";

    // Guard 2：檔案唔存在 → 靜靜 return
    if (file_exists($file)) {
        require $file;
    }
});
```

### 轉換過程

```
"App\Repositories\JsonTodoRepository"
    ↓ substr 掉走 "App\"
"Repositories\JsonTodoRepository"
    ↓ str_replace "\" → "/"
"Repositories/JsonTodoRepository"
    ↓ 拼 baseDir + ".php"
"…/app/Repositories/JsonTodoRepository.php"
```

### 兩個 guard 唔同理由

| Guard | 唔要會點 |
|---|---|
| `str_starts_with` | 亂搶第三方 class，Composer library 載唔到 |
| `file_exists` | 錯誤訊息變「檔案唔存在」而唔係「class 唔存在」，誤導 |

### 對應 `composer.json`

```json
{
    "autoload": {
        "psr-4": {
            "App\\": "app/"
        }
    }
}
```
↑ **你手寫嗰個 autoloader，Composer 只需要呢四行設定。**
改完要跑 `composer dump-autoload`。

---

---

## 📌 PDO 錯誤碼速查

```
SQLSTATE[HY000] [1049] Unknown database 'xxx'
                 ^^^^ 睇呢個數字
```

| 碼 | 意思 | 通常原因 |
|---|---|---|
| **2002** | Connection refused | **MySQL 服務未開**（XAMPP 未 Start） |
| **1045** | Access denied | 帳號 / 密碼錯 |
| **1049** | Unknown database | DB 名打錯 / 未建 |
| **1146** | Table doesn't exist | Table 未建 / 名錯 |
| **1054** | Unknown column | 欄位名打錯 |
| **1062** | Duplicate entry | 撞 UNIQUE（例：註冊重複 username） |
| **1215** | Cannot add FK | 兩邊型別唔一致（記得 UNSIGNED） |

**1062 同 2002 最常撞。**

---

## 📌 PHP 型別轉換陷阱

```php
(string) false   === ''      // ⚠️ 唔係 '0'
(string) true    === '1'
(int) true       === 1
(int) false      === 0
(bool) "0"       === false   // ⚠️ 字串 "0" 係 falsy
(bool) ""        === false
(bool) "false"   === true    // ⚠️ 非空字串一律 true
```

| 來源 | 回傳型別 | 要做 |
|---|---|---|
| `$pdo->lastInsertId()` | **string** | `(int)` cast |
| `$stmt->fetch()` 失敗 | **false** | `=== false` 判斷，唔好 truthy |
| MySQL 攞出嚟嘅所有欄位 | **string** | `(int)` / `(bool)` cast |
| `json_decode()` 失敗 | **null** | `?? []` 保護 |

---

## 📌 Array function：改嘢 vs 計嘢

```php
// 改原本個 array（by reference）
sort($arr);
unset($arr[$i]);

// 計新 array 出嚟 —— 必須接住回傳值
$arr = array_values($arr);
$arr = array_filter($arr, fn($x) => ...);
$arr = array_map(fn($x) => ..., $arr);
```

❌ 最常見錯誤：
```php
array_values($this->todos);                  // 計完即棄，冇效果
$this->todos = array_values($this->todos);   // ✅
```

**常用：**
```php
array_column($rows, "id")        // 抽出每個 row 嘅某個欄位 → [1, 2, 3]
max($ids)                        // ⚠️ max([]) 會 ValueError，空陣列要 guard
```

---

## 📌 PSR-12 快查

```php
<?php

namespace App\Models;

class Foo                        // ✅ class/interface/method 嘅 { 另起一行
{
    public function bar(): void  // ✅
    {
        foreach ($x as $y) {     // ✅ 控制結構 { 同行、關鍵字後有空格
            if ($z === false) {  // ✅ 唔好寫成一行
                return;
            }
        }
    }
}
```

- Class / interface / method 宣告 → `{` **換行**
- 控制結構（if / foreach / while）→ `{` **同行**，關鍵字後有空格
- Cast 後有空格：`(bool) $x` 唔係 `(bool)$x`
- 縮排 **4 空格**
- Method 之間空一行
- 多行陣列 / 參數尾加 **trailing comma**
- ❌ 冇 one-liner method：`public function getId(): int { return $this->id; }`

VS Code 自動清行尾空格：
```json
{
  "files.trimTrailingWhitespace": true,
  "editor.tabSize": 4,
  "files.eol": "\n"
}
```

## 📌 Debug: `Class "Xxx" not found`

臨時加一行睇 PHP 究竟搵緊咩：
```php
echo "收到: $class\n";
```

**五步排查：**

1. 收到嘅係**全名**（`App\Models\Todo`）定**淨個名**（`Todo`）？
   → 淨個名 = 用嗰度冇寫 `use`，或者寫錯
2. 檔名同 class 名一致？（**連大小寫** — Windows 唔分、Linux 分）
3. Namespace 有冇對應資料夾？（`App\Models` ↔ `app/Models/`）
4. 入口檔有冇 `require autoload.php`？
5. `$relative` vs `$class` 有冇用錯？（路徑會多一層 `App/`）

**Debug 完記得刪咗 echo。**

---

## 📌 雜項

### `??=` Null coalescing assignment

```php
$x ??= "預設值";
// 等於 if ($x === null) { $x = "預設值"; }
```

### 唔可以對 temporary 賦值

```php
$this->dehydrate($todo)["created_at"] = date(...);
// Fatal error: Cannot use temporary expression in write context
// = C++ 嘅「rvalue 唔可以做賦值左邊」
```
→ 先放入變數：`$row = $this->dehydrate($todo); $row["key"] = ...;`

### Truthy check 幾時可以用

```php
count($ids) ? ... : ...        // ✅ count() 只回傳非負 int，唯一 falsy 就係 0
$str ? ... : ...               // ⚠️ "0" 同 "" 都係 false
$num ? ... : ...               // ⚠️ 0 係 false
```

**`===` 鐵律講嘅係比較時唔用 `==`（避開型別雜耍），
唔係「所有條件判斷都要寫 `=== 0`」。兩件事。**

### PowerShell 睇 UTF-8 檔顯示亂碼

```powershell
type todos.json                     # 可能亂碼（console 用緊 Big5）
Get-Content todos.json -Encoding utf8   # ✅
chcp 65001                          # 或者切 console 做 UTF-8
```

> **「睇落亂碼」唔一定係資料錯，可能只係顯示層編碼唔夾。**

---

## 🧭 學習軌跡

| Week | 內容 | 對應 Pattern |
|---|---|---|
| Week 5 | MySQL + PDO + 真登入系統 | 1-7 |
| Week 6 | OOP → MVC 架構 | 8-9 |
| Week 7 | Interface / Autoload / Namespace / Router | 10-17 |

**Week 7 核心心法：**
> `save()` = 把 object 現時狀態寫入 DB，唔係執行操作 → **idempotent**
> Interface = 一份合約，Controller 唔使認識邊個 class，只需知對方識做咩
> `use` ≠ `require` — 前者改花名，後者載入檔案
> Namespace 每一層 = 一層資料夾（PSR-4）
