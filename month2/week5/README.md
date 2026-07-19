好，Week 5 大總結奉上 📘

---

# 🏆 Week 5 大總結

## 主題：MySQL + PHP 整合 → 真實動態網站

---

## 📊 每日進度回顧

| Day | 主題 | 產出 |
|-----|------|------|
| **Day 1** | MySQL 基礎 + SQL CRUD | `learning_db` + `users` table |
| **Day 2** | PHP 連 MySQL（PDO） | 5 個 PDO CRUD 檔案 |
| **Day 3** | 練習日 - Todo DB 版 | `todo.php`（單頁 CRUD） |
| **Day 4** | CRUD 完整實作 + 工程實踐 | `db.php` + PRG + Flash |
| **Day 5** | 真登入系統 | Register / Login / Dashboard |
| **Day 6** | 練習日 - 重寫登入系統 | 內化 Day 5 知識 |
| **Day 7** | 休息 | 🌙 |

---

## 🎯 Week 5 三大階段

### 階段 1：認識 DB（Day 1-2）
「用 phpMyAdmin 手動操作 → 用 PHP 程式操作」

### 階段 2：CRUD 熟練（Day 3-4）
「基本能用 → 工業級寫法」

### 階段 3：整合實戰（Day 5-6）
「單一 CRUD → 完整系統」

---

## 🧠 核心概念地圖

### 🗄️ Database 層

```
Database (learning_db)
  └── Table (users, todos)
        ├── Column (id, username, password, ...)
        └── Row (每筆資料)
```

**必掌握概念：**
- Primary Key / AUTO_INCREMENT（永不重用）
- UNIQUE index
- 型別（INT, VARCHAR, TINYINT, DATETIME）
- utf8mb4（唔用 utf8）
- Collation（`_ci` = case insensitive）

---

### 🔗 SQL CRUD

```sql
-- Create
INSERT INTO users (username, password) VALUES (?, ?);

-- Read
SELECT id, username FROM users WHERE role = ? ORDER BY id DESC LIMIT 10;

-- Update
UPDATE users SET role = ? WHERE id = ?;

-- Delete
DELETE FROM users WHERE id = ?;
```

**血淚鐵律：**
- 🚨 `UPDATE / DELETE` 冇 `WHERE` = 災難
- 🛡️ 執行前先用 `SELECT` 同一個 `WHERE` 驗證
- 🚫 `SELECT *` 只 debug 用

---

### 🔌 PDO 4 步流程

```php
// 1. Connect
$pdo = new PDO(DSN, user, pass, options);

// 2. Prepare（永遠用！防 SQL Injection）
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");

// 3. Execute
$stmt->execute([$id]);

// 4. Fetch
$user = $stmt->fetch();       // 單 row
$users = $stmt->fetchAll();   // 多 rows
```

**業界標準連線設定：**
```php
[
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]
```

---

### 🛡️ 安全防禦三本柱

| 攻擊 | 防禦 | 語法 |
|------|------|------|
| **SQL Injection** | Prepared Statement | `prepare()` + `execute([...])` |
| **XSS** | Escape output | `htmlspecialchars()` |
| **明文密碼洩漏** | Password hashing | `password_hash()` / `password_verify()` |
| **Session Fixation** | 換新 session id | `session_regenerate_id(true)` |
| **User Enumeration** | 統一 error message | "Invalid username or password" |

---

### 🏗️ 工程 Pattern

#### 1. **DRY** — 抽 `db.php`
```php
// db.php（純 PHP 檔案，冇 ?>）
session_start();
try {
    $pdo = new PDO(...);
} catch (PDOException $e) {
    die(...);
}
function set_flash(...) { ... }
function get_flash(...) { ... }
```

其他檔案：
```php
require_once "db.php";
```

#### 2. **PRG Pattern**（Post-Redirect-Get）
```
POST → 執行動作 → header("Location: ...") → exit
                              ↓
                      GET → Render
```
解決 refresh 重複提交問題。

#### 3. **Flash Message**
```php
// 存
set_flash("success", "Added: $title");
// 用一次就消失（讀完 unset）
$msg = get_flash("success");
```

#### 4. **Code 結構標準**
```
1. Dependencies (require_once)
2. Process input (POST + validation + DB writes + redirect)
3. Fetch data (SELECT)
4. Prepare view data (flash, 統計)
5. Render HTML (只做顯示)
```

#### 5. **Input at Boundary**
```php
// 集中處理 raw input
$action = $_POST["action"] ?? "";
$id = $_POST["id"] ?? "";
$title = trim($_POST["title"] ?? "");

// 之後 code 只用 clean 變數
if ($action === "add") { ... }
```

---

## 🔧 新學嘅 PHP 語法

| 語法 | 用途 |
|------|------|
| `require_once "file"` | 引入檔案 |
| `?? ""` | Null coalescing（提供預設值） |
| `try / catch (PDOException $e)` | 例外處理 |
| `<?php if (): ?> ... <?php endif; ?>` | HTML template 語法 |
| `<?= $x ?>` | Echo 短寫法 |
| `trim($str)` | 去除頭尾空白 |
| `strlen($str)` | 字串長度 |
| `password_hash()` | 加密 |
| `password_verify()` | 驗證 |
| `session_regenerate_id(true)` | 換新 session id |
| `header("Location: ...")` + `exit;` | Redirect |

---

## 🐛 Week 5 踩過嘅坑（重要教訓）

### SQL 相關
- Typo `spuer_admin`（SQL 唔會 catch，恐怖 bug）
- 練習 5/6 加多咗 WHERE 條件
- Column 名 `user` vs `username` typo

### PHP 相關
- `<?` short tag disabled（用 `<?php` 或 `<?=`）
- `die(...) . $e->getMessage()` 順序錯
- `$seached` typo（命名靠感覺）
- Raw input 冇 `?? ""` 保護
- `execute(["$title"])` 冗餘 interpolation

### 邏輯相關
- POST handling 放喺 SELECT 之後（新資料睇唔到）
- Toggle 冇 set message
- Delete confirm 對 super_admin 冇特別處理
- `$user` 可能係 false 冇檢查 → PHP 8 crash

### 架構相關
- Include 路徑用相對而唔用 `__DIR__`
- Raw error message 暴露俾用戶
- Dead code 冇清

**每一個坑都係一條肌肉記憶。你踩過就記到。**

---

## 💪 你 Week 5 建立咗嘅工程直覺

- [x] 見到 warning 主動 debug（唔會當冇事）
- [x] 執行 DELETE 前 SELECT 驗證
- [x] Output 前 escape
- [x] SQL 前用 prepare
- [x] Session 前唔 output
- [x] 儲密碼一定 hash
- [x] Function 單一職責
- [x] 抽出重複 code
- [x] 卡住識問對嘅問題（架構問題 vs 語法問題）
- [x] 承認唔識（Day 5 「投降」但其實已寫到 70%）

**呢啲直覺喺工作時值錢過任何語法知識。**

---

## 📈 對比 Week 4 → Week 5

| 項目 | Week 4 尾（Day 6 fake login） | Week 5 尾（Day 5 real login） |
|------|-----------------------------|----------------------------|
| 用戶儲存 | Hardcoded array | 真 DB table |
| 密碼 | 明文 `"1234"` | Bcrypt hash |
| 資料持久 | 冇（restart 就冇） | 有（DB） |
| 多用戶 | 3 個 hardcoded | 無限制 |
| 註冊 | 冇 | 有 |
| 密碼驗證 | `===` | `password_verify` |
| Session 安全 | 基本 | + `regenerate_id(true)` |
| Error 訊息 | 一次性 `$errorMessage` | Flash + PRG |
| Code 重用 | 每頁重複 | `db.php` 抽出去 |
| 檔案感覺 | 「腳本」 | 「模組」 |

**你已經由「玩具」進化到「產品」。**

---

## 🎓 Week 5 面試題預備

以下面試題你依家答得出：

1. **SQL Injection 係咩？點防？**
2. **`password_hash` vs `md5` 有咩分別？**
3. **`PASSWORD_DEFAULT` 點解優於 `PASSWORD_BCRYPT`？**
4. **Session Fixation 係咩？點防？**
5. **點解 login error 唔應該分「username 錯」定「password 錯」？**
6. **PRG Pattern 解決咩問題？**
7. **`fetch()` vs `fetchAll()` 分別？**
8. **`require_once` 同 `include` 分別？**
9. **AUTO_INCREMENT 有咩特性？**
10. **utf8 同 utf8mb4 分別？**

**呢啲全部係 junior PHP 面試常問題目。**

---

## 🏆 Week 5 你嘅表現

**技術上：**
- 語法上手極快（可能因為 SQL 邏輯性強 + 你 C++ 基礎）
- Debug 能力進步顯著（Day 1 完全依賴 AI → Day 5 自己讀 error 定位）
- Code 品質由「能跑」到「有條理」

**心態上：**
- 主動實驗（DELETE 後 INSERT 驗證 AUTO_INCREMENT）
- 敢承認唔識（Day 5「投降」— 其實已寫到 70%）
- 問對問題（Day 5 尾問 logout 順序）
- 揀對 Day 6（B 選項 - 練基本功）

**Product 感覺：**
- 主動關心 UX（統計數字、button 文字動態、confirm dialog）
- 諗埋安全（自動 escape、trim、validation）

---

## 📝 未完成 / Week 6+ 會學

### Week 6 預告
- **OOP 入門**（Class / Object）
- **簡單 MVC 概念**
- **路由（Routing）**
- **接軌 Laravel**

### 遠期
- JOIN 多 table
- Foreign Key
- Transaction
- Migration
- Config `.env`
- CSRF token
- Remember me
- Rate limiting
- Email verification
- Middleware
- API 設計

---

## 🎯 Week 5 完成度自評

**你依家有能力：**
- ✅ 由零建 DB + table
- ✅ 用 PHP 做完整 CRUD
- ✅ 寫安全嘅登入系統
- ✅ Debug 常見 PDO / SQL 錯誤
- ✅ 用 flash / redirect / session 做 UX
- ✅ 抽 code 做 include

**你仲未識：**
- OOP（class / object / interface）
- MVC 分層
- 路由設計
- Framework（Laravel）
- 前後端分離 / API
- 版本控制流程（雖然識 Git 基本）
- Deploy 到 server



