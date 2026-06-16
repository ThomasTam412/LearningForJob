# Week 1 Day 2 學習記錄

## 今日主題
- CSS 基礎
- 三種 CSS 寫法
- CSS 選擇器
- 盒子模型
- 表單樣式設計

---

## 今日學習內容

### 1. CSS 是什麼
- HTML 負責結構（網頁有什麼）
- CSS 負責樣式（網頁長什麼樣）

---

### 2. 三種 CSS 寫法
1. **行內樣式**（Inline）
   <h1 style="color: red;">標題</h1>
缺點：代碼亂，不易維護

2. **內部樣式**（Internal）
    <style>
        h1 { color: red; }
    </style>
缺點：只能用在單一頁面

3. **外部樣式**（External）✅ 實際工作用這個
    <link rel="stylesheet" href="style.css">
優點：一個 CSS 檔可以管理多個頁面

### 3. CSS 基本語法
選擇器 {
    屬性: 值;
}
1. CSS 選擇器
- 標籤選擇器：h1 { }
- Class 選擇器：.card { } （可重複使用）
- ID 選擇器：#title { } （只能用一次，不建議）
- 屬性選擇器：input[type="text"] { }
- 偽類選擇器：.btn:hover { }、input:focus { }

2. 今天學到的 CSS 屬性
**顏色**
- color：文字顏色
- background-color：背景顏色
**字體**
- font-size：字體大小
- font-family：字體
- font-weight：字體粗細
**盒子模型**
- width / height：寬高
- margin：外邊距
- padding：內邊距
- border：邊框
- border-radius：圓角
- box-shadow：陰影
- box-sizing: border-box：讓寬高包含 padding 和 border
**排版**
- display: block / inline / inline-block / flex
- text-align：文字對齊
- margin: 0 auto：左右置中
**表格**
- border-collapse: collapse：邊框合併
- tbody tr:nth-child(even)：偶數行樣式

## 今日完成的練習
### 練習 1：個人名片美化
檔案：namecard.html + style.css
成果：
- 白色卡片置中
- 名字、職稱、個人描述、興趣清單分明
- 紅色按鈕，hover 變深紅色
- 加上陰影效果
學到：
- 用 <div class="card"> 把內容包成一張卡片
- 用 margin: 50px auto 讓卡片置中
- :hover 要分開寫，不能取代原本樣式

### 練習 2：課程表美化
檔案：timetable.html
成果：
- 標題置中
- 表頭藍底白字
- 邊框乾淨整齊
- 表格內容清晰可讀
學到：
- 用 border-collapse: collapse 解決雙線問題
- 用 class="page-title" 區分不同頁面的 h1 樣式
- 為什麼推薦用 class 而不是 id

### 練習 3：聯絡表單美化
檔案：contact.html
成果：
- 表單變成漂亮的白色卡片
- 輸入框統一寬度，圓角
- 點選輸入框會變藍色（focus 效果）
- 性別 radio 水平排列
- 提交按鈕（藍）、重設按鈕（灰）區分明顯
學到：
- 用 <div class="form-group"> 包住每個欄位
- display: block 讓 label 獨立一行
- box-sizing: border-box 防止 width 爆版
- display: flex + gap 做水平排版
- 屬性選擇器 input[type="text"] 的用法

## 今日遇到的問題與解決方法
### 問題 1：陰影看不到
原因：
- box-shadow: 0 4px 8px rgba(0,0,0,1);
- 最後一個值 1 是完全不透明，導致陰影太重反而看不清楚。
解決：
- box-shadow: 0 4px 8px rgba(0,0,0,0.2);
- 透明度改成 0.2，陰影變柔和。
學到：
- rgba 的第四個值是透明度（0~1）
- 陰影通常用 0.1~0.3 比較好看

### 問題 2：按鈕只有 hover 才顯示樣式 ⭐ 經典 Bug
原因：
- 把所有按鈕樣式都寫在 .btn:hover 裡面
- 平常 .btn 沒有任何樣式
- 所以平常是純文字，只有滑鼠移上去才變成按鈕
解決：
- 拆成兩段：
- .btn { } 寫平常樣式
- .btn:hover { } 只寫變化的部分
學到：
- :hover 是「附加」效果，不是「取代」
- 選擇器寫錯會導致樣式完全失效
- 看到效果不對時，先檢查選擇器名稱

### 問題 3：想讓某個 h1 單獨置中
情境：
- namecard 的 h1 不想置中
- timetable 的 h1 想置中
解決：
- 用 class 區分：
- <h1 class="name"> （名片）
- <h1 class="page-title"> （課程表）
- 在 CSS 用 .page-title { text-align: center; }
學到：
- 不要用 h1 { } 寫共用樣式
- 用 class 命名才能精確控制
- 業界推薦 class 而不是 id

## 今日成果
### 完成了 3 個有完整 CSS 樣式的頁面：
week1/Day2/namecard.html（個人名片卡）
week1/Day2/timetable.html（課程表）
week1/Day2/contact.html（聯絡表單）
week1/Day2/style.css（共用樣式表）
### GitHub 倉庫：
https://github.com/ThomasTam412/LearningForJob

## 今日反思
### 做得好的地方
有自己思考「如何單獨置中 h1」的問題
願意自由發揮，把按鈕改成紅色，調整尺寸
遇到 Bug 沒有放棄，找到原因後修好
寫 CSS 時有對應的觀察和驗證
### 需要改進的地方
修改代碼後，注釋也要同步更新
:hover 的概念還需要多練習
box-sizing 的觀念要熟記，否則容易爆版
Flexbox 還只是基礎，需要更多練習