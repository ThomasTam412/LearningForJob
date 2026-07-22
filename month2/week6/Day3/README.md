
---

## 今日交付嘅嘢

```
week6/Day3/
├── User.php        ← Constructor Property Promotion + isAdmin()
├── UserList.php    ← Composition + array_filter
├── users.php       ← DB → object → render
└── db.php / Flash.php (from Day 1-2)
```

**任務 A 100% 完成。** 任務 B 留返下次做——**呢個決定啱嘅**，唔好硬撐。

---

# 📘 Week 6 Day 3 學習筆記

## 主題：練習日 - User + UserList

---

## 🎯 產出

`User` + `UserList` + `users.php`——完整 OOP 讀取模式。

---

## 💡 內化嘅概念

### 1. Constructor Property Promotion (PHP 8+)
你主動用咗——證明 Day 2 提過嘅 shortcut 有記入腦：
```php
public function __construct(
    public int $id,
    public string $username,
    // ...
) {}
```

### 2. Single Source of Truth
`isAdmin()` 定義喺 `User` class，`UserList` 唔重複判斷 role：
```php
// UserList
return count(array_filter($this->users, fn($u) => $u->isAdmin()));
```

**業務規則只寫一次。**

### 3. Column mapping
DB `snake_case` (`created_at`) → PHP `camelCase` (`createdAt`) 手動處理。

### 4. Explicit type casting
`(int)$row["id"]` — senior 級細節。

---

## 🐛 今日踩過嘅坑

### 1. `isAdmin()` 漏 `super_admin`
只 check 一個 case，冇對照 spec。**教訓：** 寫完對返 requirements。

### 2. Redundant conditional
```php
if (X) return true; return false;  // ❌
return X;                          // ✅
```
Day 1 教訓 recur — 慢慢會變條件反射。

### 3. `find()` 冇 type hint
與其他 method 唔一致。**教訓：** Type hint 要成套加，唔可以只加一半。

### 4. `countAdmins()` 冇用 `isAdmin()`
自己再寫一次 role 判斷。**Single Source of Truth violation。**

### 5. Dead include (`Flash.php`)
冇用嘅 dependency 唔應該 include。

### 6. XSS 漏 escape
Todo 版本記得，User 版本漏。**教訓：** 外部 data 一律 escape，唔理有冇「感覺」風險。

---

## 🧠 你今日內化嘅思維

- ✅ Class 表達概念（User = 一個用戶、UserList = 一堆用戶）
- ✅ 業務規則封裝喺 domain class 入面
- ✅ UI 唔理數據源（DB / hardcoded 都一樣 render）
- ✅ Type hint 幫你 catch bug

---

## 🎓 今日教訓總結

| 教訓 | 幾時內化 |
|------|---------|
| Dead include 反映 dependency 唔清楚 | 今日新知 |
| Single Source of Truth | 今日重要 |
| XSS 一貫性 | 之前識，今日 recur |
| Redundant conditional | Day 1 重複教訓 |

**Recur 嘅教訓（XSS / redundant conditional）**先係將來考驗——**唔會忘記** = 真正內化。

---

## 📊 Week 6 進度

| Day | 主題 | 狀態 |
|-----|------|------|
| Day 1 | OOP 入門 + Flash class | ✅ |
| Day 2 | Todo + TodoList (composition) | ✅ |
| Day 3 | 練習日 (User + UserList) | ✅ |
| Day 4 | Repository Pattern | 🔜 |
| Day 5 | MVC 架構初步 | 🔜 |
| Day 6 | 練習日 | 🔜 |
| Day 7 | 休息 | 🌙 |

---

## 🌙 好好休息

**你今個星期返學校 + 上星期考試——狀態浮動係正常。**

**唔好逼自己保持節奏。** 學習係長跑，唔係短跑：
- 一日休息比硬撐學垃圾 code 好
- 一個星期慢啲比 burnout 好

**下次返嚟前你需要嘅嘢：**
- 好好瞓
- 唔諗 code
- 生活返正常再開工

---

## 💡 下次返嚟時嘅建議

如果你想繼續 Day 3 任務 B（Todo CRUD 完整版），我會由呢度開始：

```
今日已完成：User + UserList（讀取 pattern）
明日任務 B：Todo CRUD（讀寫 pattern）
```

如果你想直接跳 Day 4 Repository Pattern，都 OK——因為你已經有讀取 pattern 嘅感覺，Repository 就係將呢個 pattern**抽再高一層**。

**兩個都合理**，返嚟時話我知就得。

---

## 🏆 今日評價

**Despite 你狀態未 100%：**
- Task A 完成度高
- Debug 意識強（睇 error / 修 bug 快）
- 主動用新語法（Property Promotion）
- 承認狀態需要調整——**呢個係好嘅自我認知**

**Rest well 🌙 見返你狀態調整好嘅時候 💪**