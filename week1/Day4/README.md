🎯 今日主題
響應式設計（Responsive Design）+ Media Query

📚 學到的核心知識
1. 響應式設計是什麼
同一份 HTML / CSS，根據不同螢幕大小自動切換版面配置。

讓網頁在手機、平板、桌機都好看。

2. Viewport Meta（必加）
HTML

<meta name="viewport" content="width=device-width, initial-scale=1.0">
沒加這行，手機會用桌機寬度顯示，整個版面壞掉
每個網頁都要加
3. Media Query 語法
單一條件：

CSS

@media (max-width: 768px) {
    /* 螢幕 ≤ 768px 時套用 */
}
多條件（範圍）：

CSS

@media (min-width: 769px) and (max-width: 1024px) {
    /* 螢幕在 769~1024 之間時套用 */
}
重點：

max-width：小於等於
min-width：大於等於
and：兩個條件都要成立
media query 是「覆蓋」原本的 CSS，不是重寫
4. 常見斷點（業界慣例）
裝置	範圍
手機	max-width: 768px
平板	769px ~ 1024px
桌機	min-width: 1025px
5. 響應式的實用技巧
calc() 計算寬度

CSS

width: calc(50% - 10px);  /* 50% 減去 gap 的一半 */
用來搭配 Flexbox 的 gap，讓多欄排版剛好填滿
width: 100% 不爆版的原因

因為你有 * { box-sizing: border-box; }
padding 和 border 算進 width 裡，不會撐破容器
💻 今日完成的程式碼
CSS

/* 手機版 (≤768px) */
@media (max-width: 768px) {
    .content {
        padding: 15px;
    }
    .product-card {
        width: 100%;
    }
}

/* 平板版 (769~1024px) */
@media (min-width: 769px) and (max-width: 1024px) {
    .product-card {
        width: calc(50% - 10px);
    }
}
成果：

桌機：一排 4 個卡片
平板：一排 2 個卡片
手機：一排 1 個卡片，填滿螢幕
⚠️ 今天踩過的坑（重要！）
坑 1：viewport 拼錯
HTML

❌ name="viewprot"
✅ name="viewport"
教訓： 拼錯一個字母，整個手機版就壞掉。要培養細節敏感度。

坑 2：media query 條件邏輯錯誤
CSS

❌ @media (max-width: 768px) and (max-width: 1024px)
   /* 兩個 max-width 用 and，結果只在 ≤768 生效 */

✅ @media (min-width: 769px) and (max-width: 1024px)
   /* 才是真正的「平板範圍」 */
教訓： 媒體查詢的條件範圍要先想清楚，不然會自己蓋自己。

🧠 學到的工作思維
複製 vs 手打的判斷

舊檔案可以複製當基礎
新學的東西必須自己手打
不要為了「規則」浪費時間抄重複的東西
觀察 → 思考 → 修改

先在 DevTools 看哪裡壞了
再思考為什麼壞
才動手改
DevTools 是前端工程師的眼睛

用手機/平板模式測試是基本功
一定要養成習慣
📝 GitHub Commit Message 建議
text

Day4: 加入響應式設計 (Media Query)

- 加上 viewport meta tag
- 手機版 (≤768px)：卡片填滿螢幕、padding 縮小
- 平板版 (769~1024px)：一排 2 個卡片
- 桌機版 (>1024px)：保持原本 250px、一排 4 個
- 使用 calc() 配合 gap 計算卡片寬度
🚀 明天預告：Day5 複習日
練習 1（必做）：個人名片頁 + 響應式
練習 2（建議）：餐廳菜單頁 + 三段響應式
不看舊程式碼，靠自己寫出來
VS Code 關掉：Emmet Tab 展開 + AI 補完