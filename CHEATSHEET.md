# My PHP Cheatsheet

> 我常用嘅 code snippets。累 / 趕時間就 copy。  
> 每次遇到「Google 過類似嘢」就順手加落嚟。

---

## 目錄
1. [PDO 連線](#pdo-連線)
2. [Prepared Statement - SELECT](#prepared-statement---select)
3. [Prepared Statement - INSERT / UPDATE / DELETE](#prepared-statement---insert--update--delete)
4. [Session Start + Flash Message](#session-start--flash-message)
5. [Password Hash + Verify](#password-hash--verify)
6. [PRG Pattern (Redirect + Exit)](#prg-pattern-redirect--exit)
7. [XSS Escape (htmlspecialchars)](#xss-escape-htmlspecialchars)
8. [OOP Class Template](#oop-class-template)

---

## PDO 連線
**幾時用：** 每個 PHP 檔案要連 DB 前

​```php
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
​```

**常見坑：**
- `?>` 唔好加喺 db.php 尾（避免意外 output）
- Charset 一定用 `utf8mb4`（唔用 `utf8`）

---

## Prepared Statement - SELECT
**幾時用：** 用戶輸入嚟 query DB（永遠用 prepared statement 防 SQL Injection）

​```php
// 單 row (return array 或 false)
$stmt = $pdo->prepare("SELECT id, username FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

// 多 rows (return array of arrays)
$stmt = $pdo->prepare("SELECT id, username FROM users WHERE role = ?");
$stmt->execute([$role]);
$rows = $stmt->fetchAll();
​```

**常見坑：**
- `?` 唔好加引號（`WHERE id = '?'` 錯）
- 唔好用 `SELECT *`（明確列出欄位）
- `fetch()` 失敗 return `false`，記得 check
- Fetch 出嚟 `id` 係 string 唔係 int（需要嘅話用 `(int)`）

---

## Prepared Statement - INSERT / UPDATE / DELETE
**幾時用：** 寫入 DB 操作（永遠用 prepared statement）

​```php
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
​```

**常見坑：**
- UPDATE / DELETE **一定**要有 `WHERE`（冇 WHERE = 影響成 table）
- 執行前先 SELECT 同一個 WHERE 驗證
- Duplicate INSERT 會拋 exception（SQLSTATE 23000）
- `lastInsertId()` 要喺 execute 之後即刻 call

--- 

## Session Start + Flash Message
**幾時用：** 用戶登入狀態、跨頁訊息（例如 "Added successfully"）

​```php
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
​```

**常見坑：**
- `session_start()` 前**唔可以** output 任何嘢（包括空格 / `<?php` 前嘅字元）
- Flash `get()` 讀完會 unset，refresh 之後訊息消失（正常）
- 要配合 PRG pattern 用（見 Entry 6）

---

## Password Hash + Verify
**幾時用：** 用戶註冊（hash）+ 用戶登入（verify）

​```php
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
​```

**常見坑：**
- 用 `PASSWORD_DEFAULT` 而唔係 `PASSWORD_BCRYPT`（未來自動升級算法）
- Password column 用 `VARCHAR(255)`（hash 長度 60，預留空間）
- Login fail 訊息**唔可以**話「username 錯」或「password 錯」，統一 "Invalid ..."
- 登入成功後**一定**要 `session_regenerate_id(true)` 防 fixation

---

## PRG Pattern (Post-Redirect-Get)
**幾時用：** 任何 POST 操作之後（防 refresh 重複提交）

​```php
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
​```

**常見坑：**
- **一定要 `exit;`**（`header()` 唔會停 script）
- `header()` 前**唔可以** output 任何嘢
- Empty validation error 可以唔 redirect（保留用戶輸入方便修改）
- Refresh 之後見到 form resubmit dialog = 冇做 PRG

---

## XSS Escape (htmlspecialchars)
**幾時用：** 任何 render 外部 data（DB / 用戶輸入 / API）到 HTML 時

​```php
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
​```

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

## OOP Class Template
**幾時用：** 建立新 domain class（例如 User / Todo / Product）

​```php
<?php
class Todo {
    // 用 Constructor Property Promotion (PHP 8+)
    public function __construct(
        public int $id,
        public string $title,
        public bool $done = false,
    ) {}
    
    // Method
    public function toggle(): void {
        $this->done = !$this->done;
    }
    
    public function isDone(): bool {
        return $this->done;
    }
}

// 用法
$todo = new Todo(1, "Buy milk");
$todo->toggle();
echo $todo->isDone() ? "Done" : "Pending";
​```

## Collection Class Template
**幾時用：** 管理一堆 domain object（例如 TodoList / UserList）

​```php
<?php
require_once "Todo.php";

class TodoList {
    public array $todos = [];
    
    public function add(Todo $todo): void {
        $this->todos[] = $todo;
    }
    
    public function find(int $id): ?Todo {
        foreach ($this->todos as $todo) {
            if ($todo->id === $id) return $todo;
        }
        return null;
    }
    
    public function all(): array {
        return $this->todos;
    }
    
    public function count(): int {
        return count($this->todos);
    }
}
​```

## DB → Object Pattern
**幾時用：** 由 DB fetch 轉成 array of objects

​```php
$stmt = $pdo->prepare("SELECT id, title, is_done FROM todos");
$stmt->execute();
$rows = $stmt->fetchAll();

$list = new TodoList();
foreach ($rows as $row) {
    $list->add(new Todo(
        (int)$row["id"],
        $row["title"],
        (bool)$row["is_done"],   // ← 必須 cast，唔係會炸
    ));
}
​```

**常見坑：**
- 一個 class 一個 file，file 名 = class 名
- Property 用 type hint（`public int $id`）catch bug
- DB fetch 出嚟嘅 boolean 係 string `"0"`/`"1"`，要 `(bool)` cast
- Constructor Property Promotion 只喺 PHP 8+ work
- 純 PHP file 唔加 `?>` 收尾（防意外 output）