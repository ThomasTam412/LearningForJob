# Week 7 Day 2 總結 — Autoloading

**日期：** 2026-07-30
**主題：** `spl_autoload_register()`
**產出：** `month2/week7/Day2/autoload.php`（5 行）

---

## 今日一句話

> **Autoloading = 用到邊個 class，先至自動去載邊個檔案。**

---

## 解決咗咩問題

**之前：**
```php
require 'Todo.php';
require 'TodoList.php';
require 'TodoRepositoryInterface.php';
require 'TodoRepository.php';
```

- 每個入口檔（`index.php` / `login.php` / `test.php`…）都要抄一次
- 加個新 class → 要去**所有**入口檔加一行
- 漏咗 → `Fatal error: Class "Todo" not found`
- 順序仲要啱（interface 要喺 implement 佢嘅 class 之前）
- Week 8 有 20 個 class → 20 行 × 每個入口檔

**之後：**
```php
require 'autoload.php';
```

一行。**而且加幾多個新 class 都唔使再郁。**

---

## 核心原理：PHP 有「求救」機制

PHP 遇到唔認識嘅 class：

```
new Todo(...)
   ↓
1. Todo 載咗未？  → 載咗 → 直接用
   ↓ 未載
2. 唔即刻 error，先問登記咗嘅 autoloader
   ↓
3. autoloader 成功 require → 當冇事發生，繼續行
   autoloader 全部搵唔到 → 先報 "Class not found"
```

**第 2 步就係 `spl_autoload_register()` 登記嘅 function。**

---

## 今日寫嘅 code

```php
<?php

spl_autoload_register(function (string $class) {
    $file = __DIR__ . "/" . $class . ".php";

    if (file_exists($file)) {
        require $file;
    }
});
```

### 三個關鍵，缺一不可

| 元素 | 作用 | 唔要會點 |
|---|---|---|
| `spl_autoload_register` | 向 PHP 登記回呼 | 冇 autoload |
| `__DIR__` | 絕對路徑，永遠指向 autoload.php 所在資料夾 | 換個目錄跑就爆 |
| `file_exists` guard | 搵唔到就靜靜 return | fatal error，阻住其他 autoloader |

### 點解一定要 `__DIR__`

```php
require "Todo.php";              // 相對於「執行入口」
require __DIR__ . "/Todo.php";   // 相對於「呢個檔案自己」
```

驗證方法：
```bash
cd Day2 && php test.php      # 兩者都行到
cd .. && php Day2/test.php   # 相對路徑爆，__DIR__ 正常
```

### 點解 `file_exists` guard 咁重要

冇 guard，打錯 class 名會出：
```
Warning: require(...Todoo.php): Failed to open stream
Fatal error: Failed opening required '...Todoo.php'
```
↑ 講緊「檔案唔存在」，但真正問題係「class 唔存在」。**誤導。**

有 guard：
```
Fatal error: Uncaught Error: Class "Todoo" not found
```
↑ 準確。

**更重要：** 將來用 Composer，PHP 會逐個試登記咗嘅 autoloader。
你一搵唔到就 fatal error → 後面嘅 autoloader 冇機會試 → 第三方 library 全部載唔到。

---

## 追蹤執行順序（今日答啱嘅題）

問題：`TodoList.php` 頂部嗰句 `require_once "Todo.php";` 洗唔洗刪？

```
唔刪：new TodoList → autoloader 載 TodoList.php → 佢自己 require Todo.php
刪咗：new TodoList → autoloader 載 TodoList.php → …… → new Todo → autoloader 載 Todo.php
```

**唔會衝突** —— `require_once` 有「載咗未」檢查，而 autoloader 只喺「未載」時被 call。

### 但要刪，三個理由

1. **Single Source of Truth** — 兩套機制做同一件事，以後問「Todo 邊度載入」答唔到
2. **失去 lazy loading** — `new TodoList` 就強制拉埋 `Todo`，就算根本冇用到
3. **⚠️ 相對路徑係計時炸彈** — `require_once "Todo.php"` 換個目錄跑就爆

**結論：全部檔案唔准再有 `require`，載入只由 autoloader 一處負責。**

---

## `require` 定 `require_once`？

Autoloader 入面**兩個都唔會出事**（同一 class 唔會 call 兩次）。

業界慣例寫 `require`：
- `require_once` 要維護「已載入清單」，有輕微 overhead
- 喺呢個場景 `_once` 嘅保護係多餘

---

## ⭐ 你今日識咗 Composer 嘅原理

```php
require __DIR__ . '/vendor/autoload.php';   // Laravel 每個 project 都有
```

本質同你五行一樣。**分別只在「class 名 → 檔案路徑」嘅推算規則：**

| | 規則 | 例 |
|---|---|---|
| **你依家** | 加 `.php` | `Todo` → `Todo.php` |
| **PSR-4** | namespace 對應資料夾 | `App\Models\Todo` → `app/Models/Todo.php` |

Day 4 學完 namespace，你會親手升級到 PSR-4 版本。

---

## ✅ 驗證結果

```
NULL  int(3)  bool(true)  bool(true)  NULL
```

同 Day 1 一模一樣（`int(3)` 因 AUTO_INCREMENT 累加，正常）。

**刪走四行 require，行為零改變 —— 呢個就係 refactoring 嘅定義。**

---

## 前後對比

| | Day 1 | Day 2 |
|---|---|---|
| 入口檔 | 4 行 require，順序要啱 | 1 行 |
| 加新 class | 每個入口檔加一行 | **乜都唔使做** |
| 載入時機 | 一次過載晒 | 用到先載（lazy） |
| 由其他資料夾跑 | 爆 | 正常 |

---

## 📝 今日反思

> 「今日非常清晰，主體明確，可能都有內容少的原因」

**內容少係主因，但更準確係「單一」。** 今日一條線由頭到尾：

```
問題（4 行 require 唔 scale）→ 概念（PHP 搵唔到會求救）
→ 你寫（3 行）→ 改良（guard）→ 驗證（跑 test）
```

Day 1 塞咗六樣（interface、幂等、hydrate、cast、PSR-12、錯誤碼），
每樣單獨睇合理，夾埋搵唔到主線。

**教學調整：一日一條主線，順手嘢一律押後。** Day 4、Day 5 照呢個節奏。

**另外：今日總共寫五行 code，但解決咗 Week 8 先浮現嘅維護問題，
同時搞清楚 `vendor/autoload.php` 做緊乜。寫得少 ≠ 學得少。**

---

## 🧭 進度

```
Week 7  [■■□□□□□]  Day 2/7
```

Day 1 ✅ Interface → **Day 2 ✅ Autoloading** → Day 3 練習（JsonTodoRepository）
→ Day 4 Namespace → Day 5 Router → Day 6 練習 → Day 7 休息

---

## 🔜 Day 3 = 練習日

**規則：**
- ❌ 唔准睇 Day 1-2 嘅 code
- ✅ 只准睇 CHEATSHEET.md
- ⏱️ 卡住自己諗 20 分鐘

**任務：** 寫 `JsonTodoRepository`，用 JSON 檔存 todo，唔掂 MySQL。

```php
class JsonTodoRepository implements TodoRepositoryInterface
```

然後 `test.php` **只改一行**：
```php
$repo = new TodoRepository($pdo);
$repo = new JsonTodoRepository(__DIR__ . '/todos.json');   // ← 改呢行
```

其餘一個字唔改，**五行輸出要一模一樣，而且唔使開 MySQL**。

**Day 3 就係 Day 1 個答案。**
你當時問「寫個 interface 但個 app 一模一樣，學嚟做咩」——
聽日改一行、唔開 DB、五個測試全過，你就會知。
