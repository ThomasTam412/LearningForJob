
---

# 📘 Week 5 Day 1 學習筆記

## 主題：MySQL 基礎（DB 概念 + SQL CRUD）

---

## 🎯 今日產出

- 建立咗 `learning_db` database
- 建立咗 `users` table（5 個 column）
- 執行過 20+ 個 SQL query（INSERT / SELECT / UPDATE / DELETE）

**目前 table 狀態：**
```
id | username | password    | role        | created_at
1  | thomas   | admin_pass  | super_admin | 20:40:05
4  | alice    | 1234        | user        | 23:04:31
5  | bob      | 0000        | user        | 23:04:31
```

---

## 🧠 核心概念

### DB 4 層架構
```
Database (learning_db)
  └── Table (users)
        └── Row (每個用戶)
              └── Column (id, username, password, ...)
```

### 型別
- `INT` — 整數
- `VARCHAR(n)` — 字串（最長 n）
- `TEXT` — 長文字
- `DATETIME` — 時間
- `BOOLEAN` — true/false

### 關鍵屬性
- `PRIMARY KEY` — 唯一識別（一 table 一個）
- `UNIQUE` — 唔可以重複（可多個）
- `AUTO_INCREMENT` — 自動 +1，**永不重用**
- `NOT NULL` — 必須有值
- `DEFAULT CURRENT_TIMESTAMP` — 自動填當下時間

### Collation
- **永遠用 `utf8mb4_unicode_ci`**
- `utf8` (3 bytes) 存唔到 emoji，`utf8mb4` (4 bytes) 先真正 UTF-8
- `_ci` = case insensitive

### Index / BTREE
- 加速搜尋嘅結構（O(log n)）
- PRIMARY / UNIQUE 自動建 index
- 之後 performance 調優嘅核心

---

## 🔧 SQL CRUD 語法

### INSERT
```sql
INSERT INTO users (username, password, role) VALUES 
('thomas', '1234', 'admin'),
('alice', 'abcd', 'user');
```

### SELECT
```sql
SELECT * FROM users;
SELECT username, role FROM users;
SELECT * FROM users WHERE role = 'admin';
SELECT * FROM users WHERE id > 1 AND role != 'admin';
SELECT * FROM users ORDER BY username DESC LIMIT 10;
```

### UPDATE
```sql
UPDATE users SET password = 'xxx' WHERE username = 'alice';
UPDATE users SET password = 'xxx', role = 'admin' WHERE id = 2;
```

### DELETE
```sql
DELETE FROM users WHERE username = 'bob';
```

---

## 📌 SQL 語法細節

| 項目 | 規則 |
|------|------|
| 字串 | **單引號** `'xxx'` |
| 比較 | 單一 `=`（唔係 `==`） |
| 唔等於 | `!=` 或 `<>` |
| Keyword | 全大寫（慣例） |
| 結尾 | `;` |
| Table/column 名 | 可用反引號 `` ` `` 包住（防保留字撞名） |
| 組合條件 | `AND` / `OR` |
| 排序 | `ORDER BY x ASC/DESC` |
| 限量 | `LIMIT n` |
| 範圍 | `BETWEEN a AND b` |
| 屬於清單 | `IN ('a', 'b')` |

---

## 🚨 血淚教訓（鐵律）

### 🔥 UPDATE / DELETE 冇 WHERE = 災難
- 會影響**成 table 每一 row**
- 業界真有工程師因此被 fire

### 🛡️ 保命習慣
1. **先寫 WHERE 再寫 SET / DELETE**
2. **執行前先用 SELECT 同一個 WHERE 驗證**
   ```sql
   SELECT * FROM users WHERE username = 'bob';  -- 先確認
   DELETE FROM users WHERE username = 'bob';    -- 再刪
   ```

### 🔒 其他鐵律
- `SELECT *` 只用嚟 debug，真實 code 指定欄位
- 固定選項用 `ENUM` 或關聯表（避免 typo bug）
- Password column 一律 `VARCHAR(255)`
- Table 都要有 `id` primary key

---

## 🐛 今日踩過嘅坑

1. **`spuer_admin` typo**
   - SQL 唔會報錯（因為對 DB 嚟講都係合法字串）
   - 教訓：固定選項唔應該用自由字串
   
2. **練習 5/6 加多咗 WHERE 條件**
   - SQL 語法識，但題目理解出錯
   - 教訓：寫 SQL 前先諗「應該返幾多 row」

3. **練習 4 尾巴漏 `;`**
   - 單獨執行 OK，多句一齊會出事

---

## 💡 概念延伸（今日提過，之後深入）

### ENUM
```sql
role ENUM('admin', 'user') NOT NULL
-- DB 直接拒絕非法值
```

### Soft Delete
```sql
-- 唔真刪，改狀態
UPDATE users SET is_deleted = TRUE WHERE id = 3;
```

### AUTO_INCREMENT 永不重用
- 刪 id=2, 3 後，新 INSERT 由 4 開始
- 目的：防資料混亂、log 追溯清晰

### 反引號 `` ` ``
- 包 table/column 名
- 遇到保留字撞名時必用

---

## 📊 CRUD 對應表

| 操作 | SQL | 危險度 |
|------|-----|--------|
| Create | INSERT | 🟢 低 |
| Read | SELECT | 🟢 極低 |
| Update | UPDATE | 🔴 高 |
| Delete | DELETE | 🔴 極高 |

**Read 唔會改資料，隨便試。Update / Delete 每次都要諗清楚。**

---

## 🎯 掌握程度自評

聽日開始前可以問自己：

1. CRUD 4 個操作對應咩 SQL？
2. `WHERE` 冇加會發生咩事？
3. `AUTO_INCREMENT` 有咩特性？
4. `utf8` 同 `utf8mb4` 分別？
5. 點解 `password` 要 `VARCHAR(255)`？
6. `SELECT *` 有咩問題？

答唔到就重溫。

---

## 📝 未完成 / 之後會學

- **PHP 連 MySQL（PDO）** ← 聽日 Day 2
- **Prepared Statement（防 SQL Injection）** ← 聽日 Day 2
- **多 table 關聯（JOIN）** ← Week 5-6
- **Foreign Key** ← Week 5-6
- **Transaction（交易）** ← Week 6+
- **Index 優化** ← Week 6+
- **`GROUP BY` / `HAVING` / 聚合函數** ← Week 6+
- **Soft Delete 實作** ← 做真實 project 時

---

## 🏆 今日評價

- **語法上手快** ✅（C++ 邏輯基礎幫到手）
- **主動實驗** ✅（DELETE 後 INSERT 驗證 AUTO_INCREMENT）
- **識分思考題 vs 執行題** ✅
- **懂得問細節**（例如 index 個數/reserved word）
- **敢自己修 bug**（typo `spuer_admin`）

**SQL 語法能力已達 junior 水平。剩返靠 Day 3 練習日打磨。**
