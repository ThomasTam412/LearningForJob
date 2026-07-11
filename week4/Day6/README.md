好，Day 6 總結奉上 📘

---

# 📅 Week 4 Day 6 學習筆記

## 主題：練習日 - Fake 登入系統

---

## 🎯 今日產出

一個完整可用嘅登入系統（4 個檔案）：

```
week4/Day6/
├── home.php         ← 首頁（根據登入狀態顯示不同 UI）
├── login.php        ← 登入表單 + 處理提交
├── dashboard.php    ← 受保護頁面
└── logout.php       ← 登出
```

3 個 hardcoded 用戶：
- thomas / 1234 / admin
- alice / abcd / user
- bob / 0000 / user

---

## 🆕 今日新學嘅技術

### 1. Redirect（頁面跳轉）
```php
header("Location: xxx.php");
exit;  // ⚠️ 一定要加
```

**兩個重點：**
- `header()` 前唔可以有任何 output
- **必須 `exit;`**，否則後面 code 仲會執行

### 2. `if/endif` HTML template 語法
```php
<?php if (條件): ?>
    HTML 內容
<?php else: ?>
    其他 HTML
<?php endif; ?>
```

比起 `if () { ... }`，混 HTML 時更易讀。

### 3. `<?= ?>` 短寫法
```php
<?= $x ?>
// 等同
<?php echo $x; ?>
```

### 4. `<label>` 包 `<input>`
```html
<label>
    用戶名: <input type="text" name="username">
</label>
```
Accessibility 好習慣。

### 5. Password input
```html
<input type="password" name="password">
```
會顯示 `••••` 而唔係明文。

---

## 🧠 核心工程觀念

### 1. Authentication vs Authorization

| 概念 | 意思 | 邊度做 |
|---|---|---|
| Authentication（認證） | 「你係邊個？」 | `login.php` 檢查帳密 |
| Authorization（授權） | 「你可唔可以入？」 | `dashboard.php` guard clause |

**每頁都要 authorize**，因為 HTTP stateless，server 每次都當「第一次見你」。

### 2. Guard Clause（守衛式子句）

Early return 思維應用喺 access control：

```php
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}
// 到呢度先可以放心當已登入
```

### 3. Session Key 命名要統一

Login 用 `$_SESSION["username"]`，dashboard 都要用同一個 key。  
唔可以 login 用 `user`、dashboard 搵 `username`。

### 4. Login 時 store 齊需要嘅資料

```php
$_SESSION["username"] = $inputUsername;
$_SESSION["role"] = $users[$inputUsername]["role"];
```

咁其他 page 唔使再查 `$users` array。

### 5. Logout 徹底清 session

```php
$_SESSION = [];
session_destroy();
header("Location: home.php");
exit;
```

真實系統仲會加 `session_regenerate_id(true)` 防 session fixation（Week 5 再講）。

### 6. Login 頁嘅雙重責任

Login page 要處理兩種 request：
- **GET**：顯示表單
- **POST**：檢查帳密 + set session + redirect

用 `$_SERVER["REQUEST_METHOD"]` 分。

### 7. 同一 URL 兩種 UI

`home.php` 根據 session 狀態顯示唔同內容——呢個係常見 pattern。

---

## 🐛 今日踩過嘅坑

1. **`$_SESSION["REQUEST_METHOD"]`** ❌  
   應該係 `$_SERVER["REQUEST_METHOD"]`

2. **`"$inputUsername"`** 多餘雙引號  
   變數本身已係 string，唔使 interpolation

3. **`dashoard.php`** typo  
   Debug 教訓：檔案名唔好用感覺打

4. **`$_SESSION["user"]` vs `$_SESSION["username"]`**  
   Session key 唔統一 → dashboard 永遠當你未登入

---

## 📊 完整 Flow 圖

```
未登入用戶
    ↓
[home.php] Welcome, Guest → 撳 Login
    ↓
[login.php] 顯示表單
    ↓ 用戶提交
[login.php] check 帳密
    ├─ 錯 → 留喺 login，顯示紅色錯誤
    └─ 啱 → set $_SESSION[username/role] → redirect
                ↓
[dashboard.php] guard clause check
    ├─ 未登入 → 踢返 login.php
    └─ 已登入 → 顯示 Hello, thomas!
                ↓ 撳 Logout
[logout.php] $_SESSION = [] + session_destroy → redirect
                ↓
[home.php] Welcome, Guest（loop 返起點）
```

---

## 💡 面試題預備

以下題目你今日之後應該答得出：

1. **PHP 點防止未登入用戶訪問受保護頁面？**
   - `session_start()` + `isset($_SESSION[...])` check + `header + exit`

2. **Authentication 同 Authorization 分別？**
   - Auth = 你係邊個；Authz = 你可唔可以做

3. **`header("Location: ...")` 之後點解要加 `exit;`？**
   - Redirect 只係送 header，唔會停 script，後面 code 仲會執行

4. **Login 成功後用咩機制記住用戶？**
   - Session（PHP 用 PHPSESSID cookie 追蹤）

5. **登出點解唔係淨係 `unset($_SESSION[username])`？**
   - 徹底清可以避免其他敏感資料殘留

---

## 🧠 今日重要領悟

### 「單獨學識」≠「識砌埋一齊」

Day 5 學嘅 session / cookie 你完全掌握。  
但今日要**同時用** 10 樣嘢（session + form + redirect + guard + template...）就會亂。

**呢個「亂」係將「知識點」變成「系統思維」嘅陣痛。**  
**只能靠重複做幾次先會消失，冇捷徑。**

Senior 唔亂，唔係因為腦力強，而係因為做過 10+ 次。  
你今日係第 1 次。

---

## 🎯 掌握程度自評

聽日開始前可以自問：

1. ✅ 我能唔能講出「login vs dashboard 檢查嘅分別」？
2. ✅ 我能唔能講出「點解 `header()` 後要 `exit;`」？
3. ✅ 我能唔能講出「Session key 命名唔統一會點」？
4. ✅ 我能唔能畫出成個系統嘅 flow？

答唔到都冇所謂，Week 5 Day 5 會再做一次（用 MySQL）。

---

## 📝 未完成 / 之後會學

- **`session_regenerate_id(true)`** 防 Session Fixation
- **Password hashing**（`password_hash()` / `password_verify()`）——今日 password 明文比較係唔安全嘅
- **Role-based 保護**（admin 先入得嘅頁面）
- **Remember me**（30 日免登入 = cookie 長期 token）
- **CSRF token**（防止跨站攻擊）
- **MySQL 取代 hardcoded `$users`**（Week 5 Day 5）
- **抽出 `auth_check.php` 用 `require`**

---

## 🏆 Week 4 完滿結束

| Day | 主題 | 狀態 |
|---|---|---|
| Day 1 | PHP 環境 | ✅ |
| Day 2 | 基本語法 | ✅ |
| Day 3 | 練習日 | ✅ |
| Day 4 | 表單處理 | ✅ |
| Day 5 | Session / Cookie | ✅ |
| Day 6 | Fake 登入系統 | ✅ |
| Day 7 | 休息 | 🌙 |
