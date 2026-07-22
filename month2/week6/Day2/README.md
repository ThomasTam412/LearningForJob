
---

# 📘 Week 6 Day 2 學習筆記

## 主題：多 Class 協作 + OOP 抽象

---

## 🎯 今日產出

```
week6/Day2/
├── Todo.php              ← Domain model class
├── TodoList.php          ← Collection class
├── db.php                ← Infra (session + PDO)
├── test_todo.php         ← Todo 單獨測試
├── test_todolist.php     ← TodoList 單獨測試
├── todo_oop.php          ← Hardcoded data + 用 OOP render
└── todo_db.php           ← DB data + 同一套 UI code
```

**7 個檔案，4 個 class 概念（Todo / TodoList / Flash / PDO）**。

---

## 🧠 核心概念

### 1. Composition（組合）
一個 class 用其他 class：
```php
class TodoList {
    public array $todos = [];  // 裝 Todo objects
    
    public function add(Todo $todo) { ... }
}
```

**係 OOP 最重要嘅結構方式之一**（另一個係 inheritance，Week 6 之後學）。

### 2. Type Hint（型別提示）

**Method 參數：**
```php
public function add(Todo $todo): void
public function find(int $id): ?Todo
public function remove(int $id): bool
```

**Property：**
```php
public int $id;
public string $title;
public bool $done;
```

**Nullable type：** `?Todo` = `Todo` 或 `null`

**Return type：** `: void` / `: int` / `: bool` / `: ?Todo`

**業界慣例：新 code 一律加 type hint**——bug 提早暴露 + IDE autocomplete 更聰明。

### 3. Abstraction（抽象）

**Aha moment：** `todo_oop.php` 同 `todo_db.php` 嘅 HTML 部分**一模一樣**，只係 data 來源唔同。

**意義：** UI 唔理數據源，只認 `$list` 嘅 API（`all()`, `count()`, `countDone()`...）

呢個係架構思維嘅起點——**Day 4 學 Repository Pattern 會將呢個推到極致**。

---

## 🔧 今日 PHP OOP 新語法

### Property Type Hint (PHP 7.4+)
```php
public int $id;
public string $title;
public bool $done;
```

### Constructor Default Value
```php
public function __construct($id, $title, $done = false) {
    // ...
}
```

### Return Type Declaration
```php
public function find(int $id): ?Todo { ... }
public function count(): int { ... }
public function add(Todo $todo): void { ... }
```

### `foreach ... as $index => $value`
```php
foreach ($this->todos as $index => $todo) {
    if ($todo->id === $id) {
        unset($this->todos[$index]);
        return true;
    }
}
```

### Object property/method access
```php
$todo->id            // property
$todo->isDone()      // method (有括號！)
```

**唔可以撈亂：**
- `$todo->isDone` = 讀 property `isDone`
- `$todo->isDone()` = call method `isDone()`

---

## 🆕 業界 idiom 學到

### 1. Constructor Property Promotion (PHP 8+)
```php
public function __construct(
    public int $id,
    public string $title,
    public bool $done = false,
) {}
```
**同時做晒 3 件事：** 宣告 property + 收參數 + 自動 assign。

（今日冇用，但要知有呢個 shortcut）

### 2. `array_filter` + arrow function
```php
public function countDone(): int {
    return count(array_filter($this->todos, fn($t) => $t->isDone()));
}
```

**Functional style**，比 foreach 更 declarative。

### 3. Assign in condition
```php
if ($key = $this->find($id)) { ... }
```

Senior 常用 pattern，但要小心（易同 `==` 混淆）。

### 4. YAGNI (You Aren't Gonna Need It)
唔洗嘅 method / feature 唔預先寫。要用先加。

---

## 🐛 今日踩過嘅坑

### 1. `find()` return type 誤解
以為 `find()` return array key，實際 return `Todo` object。導致 `unset($this->todos[$key])` 唔 work。

**教訓：** Method 嘅 return type hint 要讀清楚。有 `?Todo` 就知 return object。

### 2. `remove()` 冇 semicolon + return type 錯
```php
return 
}
```
語法 error + return type 應該係 `bool` 唔係 `?Todo`。

**教訓：** 寫 method 前先諗清楚 return 咩。

### 3. Constructor 冇 parameter type hint
Property 有 type hint，constructor 冇 → 有機會傳錯 type，錯誤訊息 later + 唔清晰。

**業界慣例：** Property + parameter 都要有 type hint，一致性。

### 4. DB fetch `is_done` 係 string，唔係 boolean
```php
new Todo($row["id"], $row["title"], (bool)$row["is_done"])
                                     ^^^^^ ← 必須 cast
```

**教訓：** DB 返嘅 boolean-like 數據要 explicit cast。有 type hint 幫你 catch，冇 type hint 就會靜靜有 bug。

---

## 🎓 你今日學到嘅思維進化

### Before Day 2
- Class 用嚟裝 function（Day 1 Flash 只係將 function 打包）
- OOP 好處感受唔到

### After Day 2
- Class 用嚟表達「概念」（Todo = 一件待辦事、TodoList = 一堆待辦事）
- **UI code 唔理數據源**（hardcoded / DB 都可以 render 同一個 view）
- Composition：class 之間協作，好似 C++ 咁組織

**你自己講：「有以前寫 C++ 嘅感覺」** — 呢個代表你腦內思維模型建立咗 ✅

---

## 💡 Optional Bonus 冇做（正常）

Add / Toggle / Delete 用 `Todo` object 嘅 flow 未親手做。

**但你已經識概念：**
```php
$todo = $list->find($id);
$todo->toggle();
// 之後 save 返落 DB（Day 4 學 Repository 時做）
```

呢個係 Day 4 Repository Pattern 嘅入口，唔急今日做。

---

## 📊 你 Week 6 進度

| Day | 主題 | 狀態 |
|-----|------|------|
| Day 1 | OOP 入門 + Flash class | ✅ |
| Day 2 | Todo + TodoList (composition) | ✅ |
| Day 3 | 練習日 | 🔜 |
| Day 4 | Repository Pattern | 🔜 |
| Day 5 | MVC 架構初步 | 🔜 |
| Day 6 | 練習日 | 🔜 |
| Day 7 | 休息 | 🌙 |

---

## 🎯 Day 3 練習日預告

方向可以係：

### 選項 A：User + UserList class
- 用同一 pattern 做 `User` + `UserList`
- 由 users table fetch
- 加 email property
- 完全獨立寫，唔可以 copy Todo

### 選項 B：Todo CRUD 完整版
- 加 add / toggle / delete
- 用 `Todo` object 貫穿整個 flow
- 開始感受「object 由 UI 流到 DB」

### 選項 C：混合
- Half A + Half B

**Day 3 前你諗一諗揀邊個，唔急今晚決定。**

---

## 🏆 今日評價

**你今日：**
- **理解速度快**（OOP 概念全部一次到位）
- **語法上手快**（C++ 基礎優勢）
- **有工程直覺**（`all()` 唔加、`array_filter` 揀對場景）
- **識自我批評**（`remove()` 卡住主動講出思路，等我 hint）
- **能感受抽象**（「有 C++ 感覺」代表思維模型 shift 咗）

**進度：** 超出計劃預期。原本以為 OOP 要 3-4 日先熟，你 2 日已經有直覺。

