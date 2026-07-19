好，Day 5 收工 🎉

---

# 📘 Week 5 Day 5 學習筆記

## 主題：真登入系統（DB + Password Hashing）

---

## 🎯 今日產出

```
week5/Day5/
├── db.php          ← PDO + session_start + flash helpers
├── register.php    ← 用戶註冊（hashed password）
├── login.php       ← 登入（password_verify）
├── dashboard.php   ← 受保護頁面
├── logout.php      ← 登出（session 清空 + regenerate）
└── home.php        ← 首頁（根據登入狀態切換 UI）
```

**一個完整、安全嘅登入系統。**

---

## 🧠 核心概念

### Password Hashing

| 概念 | 說明 |
|------|------|
| 問題 | 明文儲存密碼 → DB 被 hack = 所有用戶密碼洩漏 |
| 解決 | Hash = 單向加密，從 hash 反推唔到原密碼 |
| 加密 | `password_hash($plain, PASSWORD_DEFAULT)` |
| 驗證 | `password_verify($plain, $hash)` |
| Salt | 每次 hash 加入隨機 salt，同一密碼每次 hash 結果唔同 |
| Cost | `$2y$10$...` → cost=10，2^10=1024 rounds |
| 長度 | Hash 結果 60 字元，DB column 用 `VARCHAR(255)` |

**`PASSWORD_DEFAULT`** 自動用當前最佳算法（依家 bcrypt），PHP 升級會自動用更好嘅。

### Hash 結構拆解
```
$2y$10$FtU7d4lcGG7//Bbl.Ia2ku7IMBlnI0vKvQDTUca3usj...
 │  │  │                      │
 │  │  └── Salt (22 chars)    └── Hash
 │  └── Cost factor (10)
 └── Algorithm ($2y$ = bcrypt)
```

---

### Session 安全

| 技術 | 用途 |
|------|------|
| `session_regenerate_id(true)` | 防 Session Fixation |
| 登入後 regenerate | 換新 session id，刪舊 file |
| 登出後 regenerate | 同上，防殘留 |
| `$_SESSION = []` | 清空記憶體中嘅 session array |

**規則：登入 / 登出 / 權限變更 → 一律 `session_regenerate_id(true)`**

### Logout 正確順序
```php
$_SESSION = [];                    // 1. 清空舊資料
session_regenerate_id(true);       // 2. 換新 id + 刪舊 file
set_flash("success", "...");       // 3. 新 session 寫 flash
header("Location: home.php");      // 4. redirect
exit;
```

**關鍵理解：** `$_SESSION = []` 只清 array，session 機制仍存在。`set_flash` 寫入新 session 可以跨 redirect 保存。

---

### User Enumeration Prevention

**Login error 唔分「username 錯」定「password 錯」：**

```php
if (!$user || !password_verify($password, $user["password"])) {
    set_flash("error", "Invalid username or password");
    // ↑ 統一訊息，攻擊者唔知係邊個錯
}
```

**`||` vs `&&` 嘅安全差異：**
- `||`：`$user` false 就直接 short-circuit，唔會碰 `$user["password"]`
- `&&`：`$user` false 仲會執行 `password_verify` → crash（PHP 8）

---

## 🔧 完整 Login Flow

```
Register：
  用戶填 username + password
  → trim + validate
  → check username exists
  → password_hash()
  → INSERT DB
  → redirect login + flash success

Login：
  用戶填 username + password
  → trim + validate
  → SELECT user by username
  → password_verify()
  → 成功：session_regenerate_id(true) + set session + redirect dashboard
  → 失敗：flash error + redirect login

Logout：
  $_SESSION = []
  → session_regenerate_id(true)
  → set_flash
  → redirect home

Protected Page：
  if (!isset($_SESSION["user_id"])) → redirect login

Role-based：
  if ($_SESSION["role"] !== "admin") → redirect dashboard
```

---

## 🔧 Register Validation Checklist

```php
// 1. 空白檢查
if ($username === "" || $password === "") { ... }

// 2. Password 長度
if (strlen($password) < 6) { ... }

// 3. Password confirm
if ($password !== $passwordConfirm) { ... }

// 4. Username 已存在
$stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
// ...

// 5. Hash + INSERT
$hash = password_hash($password, PASSWORD_DEFAULT);
// INSERT ...
```

---

## 🐛 今日踩過嘅坑

### 1. Column 名 typo
```php
WHERE user = ?   // ❌ column 叫 username
WHERE username = ?  // ✅
```
**SQL 唔會 catch 呢種 error 直到 runtime。**

### 2. Raw input 冇 `?? ""`
```php
$username = $_POST["username"];      // ❌ 可能 undefined
$username = $_POST["username"] ?? ""; // ✅
```

### 3. 一開始「投降」但其實已經寫到 70%
**心態調整：** 唔識唔代表「投降」，而係「識定位問題」。卡住嘅位係 pattern 未熟，唔係能力問題。

---

## 💡 安全工程原則

| 原則 | 實作 |
|------|------|
| 永遠 hash password | `password_hash()` |
| 永遠 prepared statement | 全部 SQL query |
| 永遠 escape output | `htmlspecialchars()` |
| 永遠 regenerate session id | 登入/登出 |
| 統一 error message | 防 user enumeration |
| 後端永遠 validate | 唔信前端 `required` |
| Password 唔 trim | 密碼可能有意義空格 |
| Username 要 trim | 用戶可能意外打空格 |

---

## 🏗️ 今日系統架構

```
用戶 → home.php
        │
        ├── 未登入 → [Login] → login.php
        │                        ↓ POST
        │              password_verify()
        │                   ├── 成功 → dashboard.php
        │                   └── 失敗 → 留喺 login
        │
        ├── 未登入 → [Register] → register.php
        │                          ↓ POST
        │              password_hash() + INSERT
        │                   └── 成功 → redirect login
        │
        ├── 已登入 → [Dashboard] → dashboard.php
        │                           (guard clause 保護)
        │
        └── 已登入 → [Logout] → logout.php
                                  ↓
                        $_SESSION = [] + regenerate
                                  ↓
                        redirect home.php
```

---

## 🏆 今日評價

**你今日：**
- **獨立完成 register / login / dashboard / logout / home** — 5 個檔案
- **踩坑後自己 debug**（typo、`??`、順序問題）
- **問出高質量問題**（logout 順序 why）
- **理解安全概念**（hashing、enumeration、session fixation）
- **心態正確**：「投降」其實只係卡喺 pattern 轉換，唔係能力問題

**呢個系統嘅安全水平已經超過好多線上 production 系統。** 我講真——好多中小企嘅 login 系統都冇你今日做得齊全。

---

## 📝 未完成 / 之後會學

- **Role-based admin page** → Day 6 或 Week 6
- **Username regex validation** → Week 6（`preg_match`）
- **Email verification** → Week 6+
- **Remember me（30 日免登入）** → Week 6+
- **CSRF token** → Week 6+
- **Rate limiting（防 brute force）** → Week 6+
- **Config 分離（`.env`）** → Week 6
- **Session timeout 設定** → Week 6+
- **MVC 重構** → Week 7+ / Laravel
