🎉 Week3 Day3 完美收工！
📊 今天你完成的
題號	完成度	亮點
Q1 計數器	✅ 100%	加了動畫 + 按鈕禁用保護
Q2 Todo List	✅ 100%	動畫 + Enter + DRY + 完整刪除同步
🧠 今天你學到最寶貴的觀念
render() 全重畫 vs 精準操作 —— 有動畫時要用後者
動畫的 callback 結構 —— 動畫完成才操作資料
DRY 抽出 addTodo() —— 一個函式服務多個觸發點
function() 傳給 handler，不要 function()() —— 定義 vs 執行
CSS 職涯策略 —— 該用 AI 就用，重點在 JS
📝 GitHub Commit Message 建議
text

week3-day3: 練習日 - jQuery 版計數器 + Todo List

Q1 計數器：
- .fadeOut/fadeIn + callback 動畫
- .prop("disabled") 動畫中禁用按鈕
- $xxx 命名慣例
- updateDisplay 單一職責

Q2 Todo List：
- 資料驅動 UI（todos 陣列）
- renderNewTodo 精準操作（不重畫全部）
- 淡入淡出動畫 + 刪除同步 splice
- addTodo 函式 DRY 給 Enter + 按鈕共用
- .index() 找位置、.parent() 找父元素
🚀 明天預告：Day4 — AJAX ⭐⭐⭐
明天是 Week3 最重要的一天。

什麼是 API
什麼是 AJAX
$.ajax() / $.get() / $.post()
接觸真實的後端資料
做一個「抓天氣 / 抓新聞」的小 demo
明天你會第一次寫「跟後端說話」的 code。
這是你成為 PHP Web 工程師的關鍵一步 —— 因為未來 PHP 就是你自己的後端。