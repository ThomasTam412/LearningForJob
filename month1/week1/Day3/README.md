# Week 1 Day 3 學習記錄

## 今日主題
- Flexbox 完整學習
- 真實導航欄製作
- 商品卡片網格佈局
- Hover 動畫效果
- 圖片處理技巧

---

## 今日學習內容

### 1. 為什麼要學 Flexbox
- 現代網站幾乎都用 Flexbox 排版
- 取代了舊式的 float、position 黑魔法
- 三行 CSS 就能做出複雜佈局

---

### 2. Flexbox 核心概念
**兩個角色：**
- 父容器（Container）：用 `display: flex` 啟動
- 子元素（Items）：自動排列

**基本用法：**
```css
.container {
    display: flex;
}
```

--- 

### 3. 父容器常用屬性
```css
.container {
    display: flex;

    /* 排列方向 */
    flex-direction: row;        /* 水平（預設） */
    flex-direction: column;     /* 垂直 */

    /* 水平對齊（主軸） */
    justify-content: flex-start;     /* 靠左 */
    justify-content: center;         /* 置中 */
    justify-content: flex-end;       /* 靠右 */
    justify-content: space-between;  /* 兩端對齊 */
    justify-content: space-around;   /* 平均分布 */

    /* 垂直對齊（交叉軸） */
    align-items: center;        /* 垂直置中 */
    align-items: flex-start;    /* 頂部對齊 */
    align-items: flex-end;      /* 底部對齊 */

    /* 元素間距 */
    gap: 20px;

    /* 自動換行 */
    flex-wrap: wrap;
}
```

---

### 4. 今天學到的其他重要屬性
重置樣式
```css
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}
```

業界做法，避免瀏覽器預設樣式干擾。
- 移除清單樣式
```css
list-style: none;     /* 移除 ul 的圓點 */
```

- 移除連結底線
```css
text-decoration: none;
```

- 圖片處理
```css
object-fit: cover;    /* 圖片裁切，不變形 */
```
- 動畫效果
```css

transition: transform 0.3s;
.card:hover {
    transform: translateY(-5px);  /* 上浮 5px */
}
```
- 計算
```css
width: calc(100% - 30px);
```
- 裁切超出範圍
```css
overflow: hidden;     /* 配合圓角使用 */
```

## 今日完成的練習
### 練習 1：真實網站導航欄
檔案：navbar.html

成果：
- Logo 在左，選單在右
- 深灰背景，白色文字
- 選單水平排列，間距 30px   
- Hover 時變天藍色

核心技術：
- display: flex + justify-content: space-between
- align-items: center 垂直置中
- <ul> 改成 flex 容器讓 <li> 水平排列
### 練習 2：商品卡片網格
檔案：products.html

成果：
- 6 個商品自動排成 3x2 網格
- 每張卡片有圖片、名稱、價格、按鈕
- 滑鼠移上去卡片會上浮，有陰影變化
- 觖窗縮小時自動換行（2列 → 1列）

核心技術：
- flex-wrap: wrap 自動換行
- gap 控制卡片間距
- object-fit: cover 處理圖片
- transition + transform 做動畫
- overflow: hidden 配合圓角

## 今日重要觀念
### 1. Flexbox 的「主軸」和「交叉軸」
- 預設主軸是水平（左右）
- 交叉軸是垂直（上下）
- justify-content 控制主軸
- align-items 控制交叉軸

### 2. flex-wrap: wrap 的重要性
- 預設 flex 不換行，所有元素擠在一行
- 加入 wrap 才會自動換行
- 這是「響應式網格」的基礎

### 3. Hover 動畫三件套
```css

transition: transform 0.3s;
.card:hover {
    transform: translateY(-5px);
}
```
- 這三行組合，就是現代網站常見的卡片懸浮效果。

### 4. 圖片處理小知識
- 不同尺寸圖片塞入固定容器，會變形
- object-fit: cover 自動裁切，保持比例
- 處理頭像、商品圖、Banner 必用

## 今日成果
### 完成了 2 個現代網頁佈局：
- week1/Day3/navbar.html（導航欄）
- week1/Day3/products.html（商品列表）
- week1/Day3/style.css（共用樣式）
-兩個頁面都接近真實電商網站水準，已經可以放上線。

### GitHub 倉庫：
https://github.com/ThomasTam412/LearningForJob

## 今日反思
### 做得好的地方
- 認真手打每一行 CSS
- 主動把貨幣改成 MOP，注意在地化細節
- 完成後有試 hover 效果，驗證有沒有生效
- 中間需要暫停去處理事情，回來後能繼續
### 需要改進的地方
- Flexbox 的屬性還很多，需要更多練習熟悉
- justify-content 的各種值還記不熟
- 響應式設計還沒學，網頁在手機上看可能不好看

## 明日計劃
### 明天準備學習：
- 響應式設計（Responsive Design）
- Media Query（@media）
- 讓網頁在電腦、平板、手機都好看

### 預計練習：
- 把今天的導航欄改成手機版（漢堡選單）
- 讓商品卡片在手機上變成 1 列
