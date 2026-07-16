
---

# 📘 Week 5 Day 3（補）學習筆記

## 主題：練習日 - Todo List DB 版

---

## 🎯 產出

`todo.php` 單頁 CRUD 應用 + `todos` table

**功能：**
- 顯示所有 todo（含統計）
- 新增 todo（validation）
- Toggle 完成狀態
- 刪除 todo（confirm）
- XSS 防禦 + Prepared Statement

---

## 🧠 今日核心學到嘅嘢

### 1. 由 array 進化到 DB 嘅思維
- Week 2 Day 6 純 JS array Todo → Week 5 Day 3 DB Todo
- 資料持久化、關 browser 都仲喺度
- 未來多用戶 / 多裝置嘅基礎

### 2. Refactor 訓練
- **Before：** `$_POST[...]` 散落多處
- **After：** 集中處理 raw input，用本地變數
- 呢個叫「input sanitization at boundary」

### 3. 兩種 toggle 做法
- **方向 A**：SELECT + PHP 判斷 + UPDATE（2 次 DB query）
- **方向 B**：`UPDATE ... SET is_done = NOT is_done`（1 次，推薦）
- 你揀咗方向 B ✅

### 4. 統計數字用 PHP 做，唔係另外 SQL
```php
$total = count($todos);
$done = count(array_filter($todos, fn($t) => $t["is_done"]));
```
- 資料已經喺 memory，唔使再問 DB

### 5. UI/UX 細節
- 已完成 → `<s>` 刪除線
- Button 文字動態（Done vs Undo）
- Delete confirm dialog

---

## 🐛 今日踩過嘅坑

### 1. Table 設計漏欄位
- 一開始諗「id + todo + created_at」
- 冇 `is_done` → 冇辦法標記完成
- **教訓：先諗需求再諗 schema**

### 2. 命名衝突
- 想用 `todo` 做欄位名，同 table `todos` 撞
- **改用 `title`**
- **規則：欄位名要獨立表達語意**

### 3. `SELECT *`
- Day 2 教訓，今日又用咗一次
- **規則：真實 code 一律指定欄位**

### 4. 過度 escape
- Hardcode string 都包 `htmlspecialchars`
- **規則：只 escape 用戶輸入 / DB 讀出嚟嘅嘢**

### 5. Warning：Undefined array key
- 定義咗 `$action` 但下面仲用 `$_POST["action"]`
- **教訓：定義咗嘅本地變數要一致使用**

### 6. `die()` 用法錯
- `die("xxx") . $e->getMessage();` → 第二段永遠執行唔到
- **正確：** `die("xxx" . $e->getMessage());`

### 7. Copy-paste bug
- Delete 分支個 message 寫咗 `updated`（應該係 `deleted`）
- **教訓：copy paste 之後要逐個字讀**

### 8. 冗餘 string interpolation
- `execute(["$title"])` 多咗雙引號
- **規則：變數本身係 string 就唔使包**

---

## 💡 內化嘅工程原則

| 原則 | 你今日應用 |
|------|-----------|
| Input sanitization at boundary | ✅ 集中 `$_POST` 處理 |
| Defensive programming | ✅ `?? ""` 提供預設值 |
| DRY | ✅ 一個 `$action` 用喺 3 個地方 |
| 語意化命名 | ✅ `title` 而唔係 `todo` |
| 前後端 double check | ✅ HTML input + PHP trim + PHP validate |
| Warning 都要修 | ✅ 主動發現並解決 |

---

## 🆕 新學嘅 SQL trick

```sql
UPDATE todos SET is_done = NOT is_done WHERE id = ?
-- 或
UPDATE todos SET is_done = 1 - is_done WHERE id = ?
```

Toggle 兩個狀態嘅 SQL magic。

---

## 🏆 表現評價

**你今日：**
- **獨立寫完成個 CRUD** — 冇睇 Day 2 code ✅
- **卡住識問對嘅問題** — 問「架構點放」而唔係「code 點寫」
- **主動 debug** — Warning 主動報告
- **接受批評並改進** — Refactor 願意做
- **細心觀察** — 你嗰句「未更新顯示」證明你有睇結果同代碼對比

**對比 Week 4 Day 6 你話「亂」——今日呢個 Todo 系統你冇再話亂 😊**  
呢個就係「重複做」帶嚟嘅穩定感。
