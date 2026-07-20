
---

# 📘 Week 6 Day 1 學習筆記

## 主題：OOP 入門 + Flash 重構

---

## 🎯 今日產出

```
week6/Day1/
├── Flash.php         ← Flash class
├── db.php            ← Session + PDO + include Flash
├── counter_test.php  ← Counter 熱身
└── todo.php          ← 用 OOP Flash 重構
```

---

## 🧠 OOP 五個核心詞彙

| 詞彙 | 意思 |
|------|------|
| **Class** | 藍圖 / template |
| **Object / Instance** | 用藍圖造出嚟嘅實體 |
| **Property** | Class 入面嘅變數 |
| **Method** | Class 入面嘅 function |
| **`$this`** | 「呢個 object 自己」（同 C++ 一樣） |

---

## 🔧 PHP OOP 語法（vs C++ 對比）

| 概念 | C++ | PHP |
|------|-----|-----|
| Class 定義 | `class Foo { };` | `class Foo { }` |
| Access `$this` | `this->x` 或 `x` | `$this->x`（**必須** `$this->`） |
| Constructor 名 | `Foo()` (=class 名) | `__construct()` (magic method) |
| new object | `Foo f;` 或 `new Foo()` | `new Foo()`（永遠有 `new`） |
| Call method | `f.method()` 或 `f->method()` | `$f->method()`（永遠 `->`） |
| Method 順序 | 需 forward declaration | **冇順序限制** |
| Property default | 建構子入面 init | 可直接 `public $x = 0;` |

---

## 💡 今日兩個重要工程原則

### 1. Simple things should be simple

**用 constructor 嘅正確時機：**
- 需要 argument
- 初始化邏輯複雜
- 需要 dependencies

**Counter 呢個 case：** 直接 `public $count = 0;` 更簡潔，唔使 constructor。

**教訓：** OOP feature 唔係用得越多越好。Junior 見到 constructor 就想用；Senior 只喺需要時先用。

### 2. Redundant conditional
```php
// ❌ 冗餘
if (isset(...)) { return true; }
return false;

// ✅ 精簡
return isset(...);
```

`isset()` 本身就 return boolean，唔使再包 if。

---

## 🐛 今日踩過嘅坑

### 1. `_construct` typo（超 subtle bug）
```php
public function _construct() { ... }  // ⚠️ 一個 _
public function __construct() { ... } // ✅ 兩個 _
```
**教訓：** PHP 唔會報錯，magic method 名要小心打。Typo 令 constructor 冇被 call，但表面 work（因為 property 有 default 或 null 被當 0）。

### 2. `unset($_SESSION)` 而唔係 `unset($_SESSION["flash"][$type])`
**教訓：** 清錯範圍，摧毀成個 session。

### 3. `get()` 冇檢查 key 存唔存在
**教訓：** 讀 array key 前一定 `isset()`，PHP 8 唔 check 會 warning。

---

## 🏗️ Flash class 完成版

```php
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
```

**OOP 好處體現喺：** `get()` 內部 call `$this->has()`，method 之間可以互相協作。

---

## 🎯 你今日主動觀察嘅嘢

1. **問 method 順序**（C++ 反射） → 學到 PHP 冇 forward declaration 概念
2. **揀「保持 db.php 純粹」** → 唔將 `$flash` 塞入 db.php，職責分離做得好

---

## 📌 OOP 好處你今日仲感受唔到（正常）

**因為單一 class refactor 體現唔到 OOP 力量。** 真正發威場景：

1. 多個 class 互動
2. 繼承 / polymorphism
3. Testing（Class 可 mock，function 難 mock）
4. 大 project 命名管理

**Trust the process** — Day 4-5 學到 Repository / MVC 時，你會有第一次「wow」moment。

---

## 📝 Week 6 進度預告

| Day | 主題 |
|-----|------|
| Day 1 ✅ | OOP 入門 + Flash class |
| Day 2 | User / Todo 各自 class |
| Day 3 | 練習日 |
| Day 4 | Repository Pattern（DB 邏輯抽入 class） |
| Day 5 | MVC 架構初步 |
| Day 6 | 練習日 |
| Day 7 | 休息 |

**大方向：** Week 6 由「識寫 class」→「識用 class 組織系統」。

