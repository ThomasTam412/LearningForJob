
---

# 📘 Week 5 Day 2 學習筆記

## 主題：PHP 連 MySQL（PDO）

---

## 🎯 今日產出

5 個檔案完成 CRUD 全套操作：

```
week5/Day2/
├── db_test.php        ← 連線測試
├── users_list.php     ← 顯示所有用戶（SELECT）
├── user_search.php    ← 搜尋用戶（Prepared Statement）
├── user_add.php       ← 新增用戶（INSERT）
└── user_manage.php    ← 管理用戶（UPDATE + DELETE）
```

**首次做「資料來自 DB」嘅動態網站。**

---

## 🧠 核心概念

### PHP 同 MySQL 溝通
- PHP 靠 **Database Driver** 同 DB 傾偈
- 兩大選擇：`mysqli` vs **`PDO`**
- **揀 PDO**：跨 DB / 業界主流 / Laravel 底層都用

### 🚨 SQL Injection（今日最重要）
- **原理**：將「SQL 語法」注入 query，令 DB 執行意料之外嘅嘢
- **經典攻擊**：`' OR '1'='1` → 令 `WHERE` 永遠 true
- **後果**：資料外洩、被 bypass 登入、DB 被刪
- **20 年嚟排 OWASP Top 10**

### 🛡️ 防禦：Prepared Statement
- **原理**：SQL 語句同資料**分開送**
- **效果**：DB 將輸入當「純資料」，唔會當語法執行
- **今日親眼證實**：輸入 `' OR '1'='1` → `User not found`
- **鐵律**：永遠使用 Prepared Statement，永遠唔好將用戶輸入直接拼入 SQL

---

## 🔧 PDO 4 步流程

```
1. Connect  → new PDO(...)
2. Prepare  → $stmt = $pdo->prepare(SQL with ?)
3. Execute  → $stmt->execute([values])
4. Fetch    → $stmt->fetch() / fetchAll()
```

**INSERT / UPDATE / DELETE 唔使 fetch。**

---

## 🔧 PDO 連線 template（業界標準）

```php
$pdo = new PDO(
    "mysql:host=localhost;dbname=learning_db;charset=utf8mb4",
    "root",
    "",
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]
);
```

**兩個必加設定：**
- `ERRMODE_EXCEPTION`：DB 錯誤拋 exception（方便 debug）
- `FETCH_ASSOC`：預設 associative array（`$row["username"]` 而唔係 `$row[0]`）

---

## 🔧 各操作 code pattern

### SELECT 多 row
```php
$stmt = $pdo->prepare("SELECT id, username FROM users WHERE role = ?");
$stmt->execute([$role]);
$users = $stmt->fetchAll();  // array of associative arrays
```

### SELECT 一 row
```php
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();  // associative array 或 false
```

### INSERT
```php
$stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
$stmt->execute([$username, $password, $role]);
$newId = $pdo->lastInsertId();
```

### UPDATE
```php
$stmt = $pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
$stmt->execute([$newRole, $id]);
$affected = $stmt->rowCount();
```

### DELETE
```php
$stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
$stmt->execute([$id]);
```

---

## 🆕 Placeholder 兩種寫法

### 位置式（`?`）
```php
$pdo->prepare("... WHERE id = ? AND role = ?");
$stmt->execute([$id, $role]);
```

### 命名式（`:name`）
```php
$pdo->prepare("... WHERE id = :id AND role = :role");
$stmt->execute([":id" => $id, ":role" => $role]);
```

**兩個都常用**，多 placeholder 時 `:name` 更清晰。

---

## 🆕 try/catch 例外處理

```php
try {
    // 可能出錯嘅 code
    $pdo = new PDO(...);
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
```

**同 C++ 一模一樣**（PHP 有 OOP）。

---

## 🆕 GET vs POST（今日新學）

| 操作 | 用邊個 |
|------|--------|
| 讀資料 / 搜尋 | GET |
| **改變 server 狀態**（INSERT/UPDATE/DELETE） | **POST** |

**點解 Delete 唔可以用 GET？**
- 假 link / crawler 可以無意間觸發
- 業界標準：改變狀態嘅操作**永遠用 POST**

---

## 🆕 傳 id 嘅 form pattern

```html
<form method="POST" style="display:inline;">
    <input type="hidden" name="action" value="delete">
    <input type="hidden" name="id" value="<?= $user["id"] ?>">
    <button type="submit" onclick="return confirm('Delete?')">Delete</button>
</form>
```

**幾個技巧：**
- `<input type="hidden">` → 用戶睇唔到，但會 submit
- `style="display:inline;"` → form 唔換行
- `onclick="return confirm(...)"` → JS 一句 hack，防手滑

---

## ⚠️ 今日踩過嘅坑

### 1. `<?` short tag 唔 work
```php
<? endforeach; ?>   ❌ 預設 disabled
<?php endforeach; ?> ✅
<?= $x ?>            ✅（永遠 enabled）
```

### 2. `$seached` typo
命名靠感覺出事 → 建立習慣：**唔識拼就查**

### 3. Raw error message 暴露俾用戶
```php
$errorMessage = "Failed: " . $e->getMessage();  // ⚠️ Production 唔可以
```
**正確做法：** `error_log()` 詳細錯誤，用戶只見友善訊息

---

## 🚨 Code Review 發現嘅 3 個潛在 bug

### Bug 1：Toggle Role 冇 success message
用戶撳完冇 feedback → 加 `$message = "..."`

### Bug 2：`$user` 可能係 `false` 導致 crash
```php
$user = $stmt->fetch();
$newRole = ($user["role"] === "user") ? ...  // ⚠️ 若 $user 係 false → PHP 8 crash
```
**修法：** 加 `if (!$user)` 檢查（defensive programming）

### Bug 3：Toggle super_admin 會意外降級
`else` 分支冇考慮 super_admin → 一撳就變 user  
**修法：** 特別 case 處理 + UI 上 hide button（前後端 double check）

---

## 💡 UX 議題（今日提過，Day 4 深入）

### Double Submit 問題
- Refresh 表單提交後嘅頁面 → **會重複提交**
- **解決方案：PRG pattern（Post-Redirect-Get）**
  ```php
  // 執行完 action
  header("Location: user_manage.php");
  exit;
  ```
- Message 用 **session flash** 傳（redirect 後讀完即清）

---

## 📝 完整工程鐵律清單

| # | 鐵律 |
|---|------|
| 1 | 永遠用 Prepared Statement |
| 2 | `SELECT *` 只 debug 用，真實 code 指定欄位 |
| 3 | 改變狀態嘅操作用 POST，唔用 GET |
| 4 | Raw error message 唔 output 俾用戶睇 |
| 5 | DB query 可能返 false，永遠 check |
| 6 | 前端 + 後端 double check（唔可以只信 UI） |
| 7 | 危險操作要有 confirm |
| 8 | `<?php` 永遠寫齊，唔用 `<?` |
| 9 | 命名唔識就查，唔靠感覺 |
| 10 | 每個 DB 操作用 try/catch 包起 |

---

## 🎯 掌握程度自評（返嚟時可以問自己）

1. PDO 4 步係邊 4 步？
2. Prepared Statement 點防 SQL Injection？
3. `?` 同 `:name` 兩種 placeholder 分別？
4. `fetch()` 同 `fetchAll()` 分別？
5. `lastInsertId()` 有咩用？
6. 點解 Delete 唔可以用 GET？
7. PRG pattern 解決咩問題？

答唔到就重溫。

---

## 📝 未完成 / 之後會學

- **Password hashing**（`password_hash` / `password_verify`）→ Day 5
- **PRG pattern + Session Flash**  → Day 4
- **Defensive programming**（check DB return）→ Day 4
- **抽 DB 連線做 `db.php`**（DRY）→ Day 4
- **JOIN 多 table**  → Day 6 或 Week 6
- **Transaction**  → Week 6+
- **Foreign Key** → Week 6

---

## 🏆 今日評價

- **語法上手快** ✅ 由 SELECT 一路做到完整 CRUD 冇卡
- **主動實驗** ✅（故意錯 db name / SQL injection 測試）
- **細心度提升** ✅ 每個 output 都 escape，冇漏
- **理解 SQL Injection 嘅嚴重性** ✅ 親眼睇到擋攻擊嘅威力
- **Code 結構清晰** ✅ HTML template 用 `if:/endif:`，讀得順

**今日進度：完成度接近 100%，只欠 defensive programming 同 UX 打磨。**

**Week 5 Day 2 係整個 6 個月計劃嘅一個里程碑** — 你依家有能力寫真正嘅動態網站。
