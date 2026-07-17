
---

## Ready 收工，出 Day 4 總結 👇

---

# 📘 Week 5 Day 4 學習筆記

## 主題：CRUD 完整實作 + 工程實踐

---

## 🎯 今日產出

將上午寫嘅 `todo.php` 由「初學者 code」升級到「工業級 code」。

**新增檔案：** `db.php`（DB 連線 + Session + Flash helpers）

---

## 🧠 3 大核心技術

### 1. `require_once` — DRY 原則

**問題：** DB 連線 code 喺每個檔案重複

**解決：** 抽出 `db.php`，每個檔案 `require_once "db.php"`

**PHP include 4 個 function：**

| Function | 找唔到檔案 | 已 include 過 |
|----------|-----------|----------------|
| `include` | Warning | 再 include |
| `include_once` | Warning | 唔再 include |
| `require` | Fatal error | 再 include |
| **`require_once`** | **Fatal error** | **唔再 include** |

**業界標準：`require_once`**

**慣例：**
- 純 PHP 檔案**唔加 `?>`**（防止意外 output）
- 用 `__DIR__ . "/db.php"` 而唔係 `"db.php"`（絕對路徑更穩定）

---

### 2. PRG Pattern（Post-Redirect-Get）

**問題：** POST 後 refresh 會重複提交（Double submit）

**真實災難：**
- 電商付款 → 扣兩次錢
- 銀行轉帳 → 轉兩次
- 發文 → 兩篇一樣

**解決：**
```php
// POST → 執行動作 → 立即 redirect
$stmt->execute([...]);
header("Location: todo.php");
exit;
```

**Flow：**
```
POST → INSERT → Redirect → GET → Render
                              ↑
                        用戶最終停喺 GET 頁面
                        Refresh 只係 GET，無 side effect
```

**業界慣例：**
- 成功 action → PRG（redirect）
- Validation error → 通常唔 redirect（保留用戶輸入）

---

### 3. Session Flash Message

**問題：** Redirect 之後，`$successMessage` 變數消失

**解決：** 用 session 臨時保存訊息，讀完立即清

**兩個 helper：**

```php
function set_flash($type, $message) {
    $_SESSION["flash"][$type] = $message;
}

function get_flash($type) {
    if (isset($_SESSION["flash"][$type])) {
        $message = $_SESSION["flash"][$type];
        unset($_SESSION["flash"][$type]);  // ← 讀完立即清
        return $message;
    }
    return null;
}
```

**使用：**
```php
// POST 分支
set_flash("success", "Added: $title");
header("Location: todo.php");
exit;

// Render 前
$successMessage = get_flash("success");
```

**設計特點：**
- Nested array 結構：`$_SESSION["flash"]["success"]`
- 唔會撈亂其他 session（例如登入 username）
- 「用一次就消失」——refresh 訊息會冇

---

## 🏗️ Code 結構模式（業界標準）

```php
<?php
// 1. Dependencies
require_once "db.php";

// 2. Process input
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // POST handling + validation + DB writes + redirect
}

// 3. Fetch data
$stmt = $pdo->prepare("SELECT ...");
$stmt->execute();
$data = $stmt->fetchAll();

// 4. Prepare view data
$total = count($data);
$successMessage = get_flash("success");
$errorMessage = get_flash("error");
?>
<!DOCTYPE html>
<!-- 5. Render HTML (只做顯示，冇 logic) -->
```

**Laravel Controller 都係咁組織**，只係佢會抽做獨立 method。

---

## 🎁 Bonus 提及嘅概念

### Config 分離
真實 project 會用 `config.php` 或 `.env` 存 DB 密碼，Git 唔 commit（安全 + dev/prod 分開）。

### Development vs Production error handling
- Dev：raw error message（方便 debug）
- Prod：`error_log()` + 友善訊息

---

## 🐛 今日踩過嘅坑

### 1. `db.php` 結構混亂（幾乎踩到）
- 提示：睇你有冇兩個 `<?php` / include 位置
- 一發現即刻修

### 2. `require_once` 位置錯（初期放喺 `$errorMessage = ""` 之後）
- 修返到最頂
- **教訓：dependencies 永遠喺檔案最頂**

### 3. Dead code
- Refactor 後留低 `$errorMessage = ""; $successMessage = "";`
- 冇 delete → 誤導後嚟讀 code 嘅人
- **教訓：refactor 完要清舊 code**

### 4. Flash message 讀嘅位置
- 一開始擺喺 HTML 部分（`<h1>` 之後）
- 應該喺 PHP 段落最尾
- **教訓：PHP 邏輯同 HTML 分離**

---

## 💪 你今日內化嘅工程原則

| 原則 | 應用 |
|------|------|
| DRY | DB 連線抽 include |
| Separation of Concerns | PHP 邏輯 vs HTML 顯示 |
| Fail loudly | `require_once` 用 fatal error 而唔係 warning |
| Idempotent operations | PRG 令 refresh 安全 |
| User experience | Flash message 保留 feedback |
| Convention over configuration | 業界慣例（`?>`、`__DIR__`、function 命名） |

---

## 🏆 表現評價

**你今日：**
- **同時接受 3 個新概念** 冇話「亂」
- **主動觀察** 例如：睇到「訊息消失咗」證明真係 test 過
- **接受批評並改進** dead code、flash 讀取位置全部改好
- **能夠貼完整 code** 而唔係只貼片段（好嘅溝通習慣）

**你 `todo.php` 依家嘅結構質素接近 senior 水平。** 剩返學 OOP + framework，就會完全達到 professional 標準。

---
