
---

# 📘 Week 6 Day 5 學習筆記

## 主題：Repository Pattern

---

## 🎯 今日產出

```
week6/Day5/
├── db.php               ← Session + PDO + Flash
├── Flash.php
├── Todo.php             ← + $createdAt property
├── TodoList.php
├── TodoRepository.php   ← 今日核心
└── todo_repository.php             ← 純 UI，冇 SQL
```

**你完成咗一個真實工業級架構。**

---

## 🧠 核心概念

### 1. Repository Pattern
**「將所有 DB 操作抽入一個 class」**

```php
class TodoRepository {
    private PDO $pdo;
    
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }
    
    public function findAll(): TodoList { ... }
    public function findById(int $id): ?Todo { ... }
    public function create(string $title): Todo { ... }
    public function toggle(int $id): void { ... }
    public function delete(int $id): void { ... }
}
```

### 2. Dependency Injection (DI)
**「唔喺 class 內 new，由外面傳入」**

```php
// ❌ 唔好
class TodoRepository {
    public function __construct() {
        $this->pdo = new PDO(...);  // 綁死
    }
}

// ✅ 好
class TodoRepository {
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;  // 外面控制
    }
}
```

### 3. Persistence Ignorance
**「Domain code 唔知道 persistence 層存在」**

你 `todo.php` 冇 SQL、冇 `$pdo->prepare()`——**佢完全唔知道背後係 MySQL、PostgreSQL、定 JSON file。**

Laravel Eloquent、Doctrine ORM 都係呢個思想。

---

## 🔧 Before vs After

| | Before | After |
|--|--------|-------|
| SQL string 數量 | 5 個 | 0 個 |
| `$pdo->prepare()` in UI | 4 次 | 0 次 |
| DB → object 轉換 | Inline | Repository |
| 統計邏輯 | Inline loop | TodoList method |
| UI 檔案關心 DB？ | **係** | **完全唔關心** |

**你嘅 `todo.php` 由「一半 DB 一半 UI」變成「純 UI」。**

---

## 💡 今日重要嘅設計決定

### 決定 1：Thin Repository（派別 A）
Toggle / Delete 用**一句 SQL**，唔 findById 再改。

```php
public function toggle(int $id): void {
    $stmt = $this->pdo->prepare("UPDATE todos SET is_done = NOT is_done WHERE id = ?");
    $stmt->execute([$id]);
}
```

**Repository 只做 data access，唔做 domain logic。**

### 決定 2：Create → findById 返完整 object
```php
public function create(string $title): Todo {
    // INSERT
    $newId = $this->pdo->lastInsertId();
    return $this->findById($newId);  // ← 攞埋 created_at 等 DB 生成欄位
}
```

### 決定 3：Return type void / bool 選擇
今日用 `void`（適合 UI 100% 信 id 有效）。將來寫 API 用 `bool`（俾 caller 判斷）。

---

## 🎓 你今日內化嘅 Senior 思維

### 1. 主動問「toggle 應唔應該 findById」
→ 你思考「一致 API」，senior 級反射

### 2. 主動問「delete 冇 find 先，正路唔係要 confirm 有呢樣先咩」
→ 你思考 defensive programming

### 3. 「確然係唔同啲呀」
→ 親身感受到 Repository 好處，非「理論上明」

---

## 🐛 今日踩過嘅坑

### 1. `findById` SQL 冇 `created_at`
但 `new Todo()` 想 pass `$row["created_at"]` → 隱藏 bug（`findAll` test 覆蓋唔到）

**教訓：** 加新 column 要**同時**改晒所有 SELECT。

### 2. `$todo->done` vs `$todo->isDone()` 唔一致
Render 用 property，但 senior 應該用 method（Single Source of Truth）

### 3. Include chain 重複
`todo.php` 手動 include `Todo` + `TodoList`，其實 `TodoRepository.php` 已經 include 咗，可以精簡

---

## 🎁 我今日提過（Optional，將來用）

### Constants + private helper（refactor 進化）
```php
class TodoRepository {
    private const COLUMNS = "id, title, is_done, created_at";
    
    private function mapRowToTodo(array $row): Todo {
        return new Todo(
            (int)$row["id"],
            $row["title"],
            (bool)$row["is_done"],
            $row["created_at"],
        );
    }
    
    public function findAll(): TodoList {
        $stmt = $this->pdo->prepare("SELECT " . self::COLUMNS . " FROM todos");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        $list = new TodoList();
        foreach ($rows as $row) {
            $list->add($this->mapRowToTodo($row));
        }
        return $list;
    }
    // findById 同樣 call mapRowToTodo
}
```

**加新 column 只改一處。**

---

## 📊 Week 6 進度

| Day | 主題 | 狀態 |
|-----|------|------|
| Day 1 | OOP 入門 + Flash class | ✅ |
| Day 2 | Todo + TodoList (composition) | ✅ |
| Day 3 | 練習日 (User + UserList) | ✅ |
| Day 4 | **Cheatsheet Day** | ✅ |
| Day 5 | **Repository Pattern** | ✅ |
| Day 6 | MVC 初步 | 🔜 |
| Day 7 | 練習日 / 休息 | 🌙 |

---

## 🎯 掌握程度自評

1. Repository Pattern 解決咩問題？
2. Dependency Injection 好處係咩？
3. Persistence Ignorance 意思？
4. 邊個 case 用 findById，邊個 case 一句 SQL？
5. 加新 column 要改邊幾個地方？

**答唔到就 review。** 大部分你答得出就好。

---

## 📝 未完成 / 之後會學

- **MVC 初步**（Day 6）：Controller 抽出 POST handling
- **Interface / Abstract class**（Week 7）：`RepositoryInterface`，令 Repository 可換
- **Autoloading**（Week 7）：唔使手動 `require_once` 每個 class
- **Namespace**（Week 7）：`App\Repository\TodoRepository`
- **Mock testing**（Week 8+）：用 fake Repository 測試

---

## 🏆 今日評價

- **語法完全冇卡** ✅
- **主動問 design 問題**（toggle findById / delete find first）✅
- **感受 pattern 好處**（「確然係唔同啲呀」）✅
- **debug 自己 catch bug**（findById SQL 漏 column）✅

**你 Week 6 中段狀態調整好，Day 5 進度回復高質量。**

**Rest well 🌙 見你 Day 6 💪**

