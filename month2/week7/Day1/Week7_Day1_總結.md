# Week 7 Day 1 總結 — Interface / Abstract Class

**日期：** 2026-07-29
**主題：** Interface、Repository Pattern 深化、Persist vs Command
**產出：** `month2/week7/Day1/`（4 個 class + 1 個 test）

---

## 一句話總結今日

> 由「Controller 綁死 MySQL」變成「Controller 只認一份合約」，
> 順手修好一個真實嘅 idempotency bug。

---

## 📁 今日產出

```
month2/week7/Day1/
├── Todo.php                        Domain Model（?int $id + setId + toggle）
├── TodoList.php                    Collection
├── TodoRepositoryInterface.php     合約（4 個 method）
├── TodoRepository.php              PDO 實作（implements 上面）
└── test.php                        CLI 測試腳本
```

**依賴方向：**
```
Controller → TodoRepositoryInterface ← TodoRepository → Todo
             「要識做咩」              「我用 MySQL 做」  「一件事本身」
```

高層（Controller）唔再依賴低層（PDO），兩者都依賴中間嗰個抽象。
呢個叫 **Dependency Inversion Principle**（SOLID 嘅 D）。

---

## 🎯 學到嘅核心概念

### 1. Interface = 合約，由 PHP 強制執行

```php
class TodoRepository implements TodoRepositoryInterface
```

漏咗一個 method、簽名對唔上 → **Fatal error，喺你行 code 之前就鬧你**。
唔靠記性，靠語言 enforce。

**咩唔入 interface：**
- `private` method（`hydrate` 係 PDO 專屬，JSON 版會有自己嘅 `loadFile`）
- `__construct`（`new` 一定要打具體 class 名，發生喺 polymorphism 之前）
- property（語法唔准）

### 2. ⭐ Persist vs Command（今日最大收穫）

**寫錯咗嘅版本：**
```php
"UPDATE todos SET is_done = NOT is_done WHERE id = ?"
```

問題：`save()` 做兩次，DB 翻轉兩次。**同一個 object 冇變過，結果唔同。**

**正確：**
```php
"UPDATE todos SET title = ?, is_done = ? WHERE id = ?"
```

> `save()` 嘅責任係「把 object 現時嘅狀態寫入 DB」，
> 唔係「執行某個操作」。

狀態改變喺 domain 層：
```php
$todo = $repo->findById(5);   // DB → object
$todo->toggle();              // 改變喺 object 發生
$repo->save($todo);           // object → DB，照抄
```

### 3. Idempotency（幂等）

> 同一個操作做一次同做十次，結果一樣。

HTTP `PUT` / `DELETE` 都要求幂等。今日寫嘅測試：

```php
$before = $repo->findById($id)->isDone();
$repo->save($todo);
$repo->save($todo);
$repo->save($todo);
var_dump($before === $repo->findById($id)->isDone());   // 必須 true
```

改之前：`false`（bug）
改之後：`true` ✅

### 4. Hydration

DB row（array）→ Domain object。抽成 `private hydrate()`，`findAll` / `findById` 共用。
DRY，而且將來加欄位只改一處。

### 5. Type Cast 紀律

| 位置 | 要做 | 唔做會點 |
|---|---|---|
| `lastInsertId()` | `(int)` | strict_types 下 TypeError |
| `isDone()` 落 DB | `(int)` | `(string) false === ''` → MySQL STRICT mode 爆 |
| `$row["id"]` | `(int)` | 型別唔一致，`===` 比較會出事 |

**唔好依賴 PHP 隱式轉換。** 今日行到，唔代表 production 行到。

---

## 🐛 今日撞過嘅坑

| 坑 | 錯喺邊 | 教訓 |
|---|---|---|
| `$id = null` 排第一 | 有預設值嘅參數後面唔可以有冇預設值嘅 | Deprecated，個 `= null` 直接被無視 |
| `SET is_done = NOT is_done` | 將 Week 6 嘅 toggle 邏輯搬入 save | Repository 唔應該有 domain 行為 |
| `Todo["id"]` | object 用 `->` 唔用 `[]` | C++ 轉 PHP 常見手誤 |
| `array_values($arr);` | 冇接住回傳值 | 分清「改嘢」定「計嘢」嘅 function |
| `Unknown database 'your_db'` | 抄咗 placeholder 冇改 | **學識讀 SQLSTATE 錯誤碼** |
| PSR-12 一堆 | `{` 位置、one-liner method、cast 空格 | 業界標準，唔係品味 |

---

## ✅ 測試結果

```
NULL          ← 新 Todo，id 係 null
int(2)        ← save() 之後回填咗 id ⭐
bool(true)    ← Idempotency 通過 ⭐
bool(true)    ← Toggle 生效
NULL          ← Delete 成功
```

`int(2)` 而唔係 `int(1)`：`AUTO_INCREMENT` 唔會因為失敗 / 刪除而回收號碼。正常行為。

---

## 💡 額外收穫

**PHP 內置 interface** — `Countable` / `IteratorAggregate` / `ArrayAccess` / `JsonSerializable`
implement 咗，PHP 嘅語言功能（`count()`、`foreach`）就識用你個 class。
Laravel `Collection` 就係咁做。

> **Interface 唔止你自己寫嘅 —— PHP 同 framework 都定義咗一堆等你 implement。**

**PDO 錯誤碼** — 2002（MySQL 未開）、1045（密碼錯）、1049（DB 唔存在）、
1146（table 唔存在）、1054（欄位錯）、1062（撞 UNIQUE）、1215（FK 型別唔夾）

---

## 📌 手尾（Day 2 開始前）

- [ ] `array_values($this->todos);` → `$this->todos = array_values($this->todos);`
- [ ] `countPending()` 改用恆等式（`count() - countDone()`）
- [ ] VS Code 開 `"files.trimTrailingWhitespace": true`
- [ ] Git commit + push（新機第一次，順便驗 SSH key）

```bash
git add .
git commit -m "Week7 Day1: Interface + Repository pattern, fix non-idempotent save"
git push
```

---

## 🔜 Day 2 預告：Autoloading

今日個 `test.php` 頭四行：

```php
require 'Todo.php';
require 'TodoList.php';
require 'TodoRepositoryInterface.php';
require 'TodoRepository.php';
```

**問題：**
- 每個入口檔都要抄一次
- 加個 class 就要去所有入口檔加一行
- 漏咗 → `Class "Todo" not found`
- 順序仲要啱（interface 要喺 implement 佢嘅 class 之前）

**聽日：** `spl_autoload_register()` — 四行變一行，
之後加幾多個 class 都唔使再郁。

然後你會明白 `composer install` 到底幫你做緊咩。

---

## 🧭 進度

```
Week 7  [■□□□□□□]  Day 1/7
Month 2 [■■■■■■□□] Week 6 完 → Week 7 進行中
```

**Day 1 ✅ Interface** → Day 2 Autoloading → Day 3 練習（JsonTodoRepository）
→ Day 4 Namespace → Day 5 Router → Day 6 練習 → Day 7 休息

**終點：** `week7/` 變成一個 mini Laravel（`public/index.php` + `app/` + `routes.php`）
