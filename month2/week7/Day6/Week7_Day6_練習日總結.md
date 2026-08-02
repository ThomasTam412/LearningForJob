# Week 7 Day 6 練習日總結 — Notes App

**日期：** 2026-08-02
**任務：** 由零手打 8 個檔案，完整 mini framework
**規則：** 唔准睇 week7 舊 code，只准睇 CHEATSHEET

---

## ✅ 完成度：4/4 功能全通

| 功能 | 狀態 | 證據 |
|---|---|---|
| 列表 | ✅ | 三條 note 顯示 |
| 新增 | ✅ | title + body 都存到 |
| 刪除 | ✅ | Id 2 消失 |
| 切換 | ✅ | Id 1 顯示 Pinned |
| `nl2br` | ✅ | Id 3 body 顯示四行 |
| `body = ""` | ✅ | Id 4 空 body 冇爆 |

**比 Day 5 個 Todo 多咗三樣：** `body` 欄位、帶參數嘅 POST、hidden input pattern。

---

## 📊 錯誤分類（重點睇呢個）

### A. 概念問題（2 個）— ⚠️ 最需要補

**A1｜`<?= ?>` 混入 echo**
```php
echo '<input value="<?= ' . $id . ' ?>">';   // ❌
echo '<input value="' . $id . '">';          // ✅
```

混淆咗兩個世界：

| 情境 | 寫法 |
|---|---|
| HTML 檔案入面插 PHP | `<input value="<?= $id ?>">` |
| **PHP 入面 echo HTML** | `echo '<input value="' . $id . '">';` |

已經喺 PHP 入面，唔使再開 PHP 標籤。

**A2｜運算子優先級搞錯（兩次）**
```php
(int) $_POST["id"] ?? 0        // ❌ cast 先行，?? 永遠唔會觸發
(int) ($_POST["id"] ?? 0)      // ✅ 先攞安全值再 cast

$x ? "a" : "b" . "\n"          // ❌ . 綁咗去 "b"
($x ? "a" : "b") . "\n"        // ✅
```

> **唔肯定就加括號。冇人會怪你括號多。**

---

### B. 重複犯嘅錯（2 個）— 未成肌肉記憶

**B1｜Method 漏 `: void`**
```php
public function add(Note $note)          // ❌
public function add(Note $note): void    // ✅
```
Day 3 `setCreatedAt` 犯過，今日 `add` 再犯。

**B2｜INSERT 分支漏 `return`**
```php
if ($note->getId() === null) {
    ...
    $this->writeAll($rows);
    return;                   // ← 漏咗就會繼續行落 UPDATE loop
}
```
Day 3 寫過，今日又漏。

---

### C. 手誤 / 打錯（4 個）

| 錯 | 正確 | 特點 |
|---|---|---|
| `max([$ids])` | `max($ids)` | 多包一層 → TypeError |
| `$rows[] = $note` | `$rows[] = $this->dehydrate($note)` | push 咗 object，json_encode 出 `{}` |
| `n12br` | `nl2br` | **n**ew **l**ine **2** **br**，字型 `l`/`1` 陷阱 |
| `is_Pinned` | `is_pinned` | 大細階唔一致 |

---

### D. HTML 基礎（2 個）— Emmet 關咗要自己記

| 錯 | 後果 |
|---|---|
| `values="..."` | **靜靜地失敗** — form submit 到但值消失 |
| `<textarea>` 冇閂 | 之後所有 HTML 被食入 textarea 入面 |

⚠️ 成對 tag：`<textarea>` `<button>` `<form>` `<div>` `<select>`
自閉合：`<input>` `<br>` `<img>` `<hr>`

---

### E. 設計 / UX（3 個）

**E1｜Guard 只 `return` 唔 output → 白畫面**
```php
if ($note === null) {
    return;                          // ❌ 用戶見到空白頁
}

if ($note === null) {
    header("Location: /notes");      // ✅ 用戶操作 → redirect
    exit;
}
```
**用戶操作（form submit）用 redirect，API endpoint 先用 404。**

**E2｜Truthy check 用喺已知型別上**
```php
if (!$note)            // ❌ findById 回傳 ?Note，只有兩種可能
if ($note === null)    // ✅
```

**E3｜Routes key 冇對返 form action**
```php
"POST /delete"           // ❌ form action="/notes/delete"
"POST /notes/delete"     // ✅
```
**改 route 一定要同時對 form / link。**

---

### F. IDE 陷阱（1 個）

```php
use NoRewindIterator;    // Intelephense 自動 import 亂加
```
你打 `No...` 佢估錯。**Commit 前掃一眼檔頂啲 `use`。**

---

## 🎯 進步對比

| | Day 3（JsonTodoRepository） | Day 6（成個 stack） |
|---|---|---|
| 完成度 | 卡到要拆四層提示 | 獨立完成 8 個檔 |
| 錯誤性質 | 「唔識點寫」 | 「漏咗 / 打錯」 |
| 新東西 | 冇 | body 欄位、POST 帶參數、hidden input |

> **「唔識」同「漏咗」係兩個唔同階段。你已經過咗第一個。**

---

## 📋 未修嘅手尾

- [ ] `toggle()` guard 加 redirect + exit（唔好淨係 return）
- [ ] `!$note` → `=== null`
- [ ] Body 移去 Created At 之前（內容行先，metadata 最後）
- [ ] 每條 note 之間加 `<hr>`（依家三條黐晒埋，睇唔出邊個掣屬邊條）
- [ ] `store()` 考慮擋空 title

---

## 💡 下次練習日改進

**記低「停低諗」嘅時刻，唔止「寫錯」。**

我今日睇到嘅係結果（你寫咗咩），睇唔到過程（你查咗幾多次 Cheatsheet、邊個位停低諗咗五分鐘）。

**後者先係「未內化」嘅真信號。**

建議格式：
```
14:20  hydrate 個 cast — 查咗 Pattern 15
14:35  routes.php 用 return 定 $routes = — 諗咗 3 分鐘
15:02  hidden input 點寫 — 完全唔識，要問
```

---

## 🧭 Week 7 完成

```
Week 7  [■■■■■■□]  Day 6/7
```

Day 1 ✅ Interface → Day 2 ✅ Autoloading → Day 3 ✅ 練習
→ Day 4 ✅ Namespace → Day 5 ✅ Router → **Day 6 ✅ 練習** → Day 7 休息

**Week 8 = Integration Week**（第一個）
- 唔學新嘢
- Week 5-7 全套做一個綜合 project
- 會撞到 schema migration 問題
