🏆 Day5 完美收工！
📘 Week2 Day5 學習總結
🎯 今日主題
DOM 操作：選取元素、改變內容/樣式、事件處理

📚 學到的核心知識
1. DOM 是什麼
瀏覽器把 HTML 變成一棵「樹狀結構的物件」，讓 JS 可以操作它。

每個 HTML 元素都是一個 Object，有屬性（textContent、style…）和方法（addEventListener…），跟物件導向程式設計概念一樣。

2. 選取元素的 4 種方法
方法	範例	推薦度
getElementById	document.getElementById("title")	舊
getElementsByClassName	document.getElementsByClassName("msg")	少用
querySelector	document.querySelector("#title")	⭐ 推薦
querySelectorAll	document.querySelectorAll(".msg")	⭐ 推薦（多個）
鐵律：新 code 一律用 querySelector / querySelectorAll。

3. 改變內容
JavaScript

title.textContent = "新文字";   // ✅ 安全，推薦
title.innerHTML = "<b>粗體</b>"; // ⚠ 有 XSS 風險
鐵律：能用 textContent 就不要用 innerHTML。

4. 改變樣式（兩種方法）
方法 A：直接改 style（小改動）
JavaScript

title.style.color = "red";
title.style.fontSize = "30px";   // 駝峰式！
方法 B：切換 class（大改動，推薦 ✅）
JavaScript

title.classList.add("highlight");
title.classList.remove("highlight");
title.classList.toggle("highlight");   // ⭐ 超常用
title.classList.contains("highlight"); // 回傳 true/false
鐵律：能用 class 切換就不要直接改 style，分離「樣式」和「狀態」。

5. 事件處理
JavaScript

元素.addEventListener("事件名", 函式);
常見事件：click、mousemove、keydown、submit、load

鐵律：

用 addEventListener，不用 onclick =
事件名稱不加 on：✅ "click"，❌ "onclick"
6. 時間控制（補充學）
JavaScript

setTimeout(() => { ... }, 1000);   // 1 秒後執行一次
setInterval(() => { ... }, 1000);  // 每秒執行一次
順便接觸到 非同步（Asynchronous） 概念：

JS 不會卡在 setTimeout 等
它會繼續往下跑，時間到了才執行回呼
💻 今日完成的程式碼亮點
JavaScript

// 主題切換器（Dark Mode）
themeBtn.addEventListener("click", () => {
    body.classList.toggle("dark-mode");
    const isDark = body.classList.contains("dark-mode");
    const mode = isDark ? "深色" : "淺色";
    console.log(`切換到 ${mode}`);
    themeBtn.textContent = isDark ? "深色模式中" : "切換主題";
});
這段 code 用上了：

DOM 選取（querySelector）
事件監聽（addEventListener）
class 切換（toggle / contains）
三元運算子
Template Literals
const 偏好
⚠️ 今天踩過的坑（重要！）
坑 1：boolean === true 的冗餘
JavaScript

❌ classList.contains("dark") === true ? ...
✅ classList.contains("dark") ? ...
任何方法/表達式回傳的 boolean，不要再 === true。

坑 2：變數宣告位置 = 設計決策
變數用途	宣告位置
需要在多次事件間累積（例如 count）	事件外（閉包）
每次事件重算（例如 mode）	事件內
原則：變數生命週期盡可能短。

坑 3：忘記讀完整需求
主題切換器有 3 個需求（切 class、改按鈕文字、Console log），第一版漏了「改按鈕文字」。

教訓：開始寫前先用註解列 TODO，逐項打勾。

🧠 學到的工作思維
動手玩才有感覺 —— 點 17 下 ✅
新練習用新檔案 —— 不要污染舊 code
textContent vs innerHTML —— 安全意識（XSS）
CSS 寫樣式，JS 切狀態 —— 分離關注點
閉包初體驗 —— 函式記住外面的變數
📝 GitHub Commit Message 建議
text

week2-day5: DOM 操作、事件處理

- querySelector / querySelectorAll
- textContent / innerHTML（XSS 意識）
- style 直接改 vs classList 切換
- addEventListener + click 事件
- setTimeout / setInterval 補充
- 練習：主題切換器（Dark Mode toggle）
- Code Review：=== true 冗餘、變數宣告位置
🚀 明天預告：Day6 — 練習日 💪
明天整合本週所有內容做綜合練習。

預計題目方向（可能調整）：

計數器（按鈕 +1 / -1 / Reset，DOM + 函式 + 事件）
小型 Todo List（input + 按鈕 + 顯示列表，較難）
猜數字遊戲 或 表單驗證 （二選一）
⚠ 明天規則：不准看本週舊 code，全靠自己。