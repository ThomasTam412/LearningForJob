📘 Week3 Day1 學習總結
🎯 今日主題
jQuery 入門：為什麼學 + 基本選取 / 事件 / 樣式

📚 學到的核心知識
1. 為什麼學 jQuery（職涯關鍵）
技術	學的理由
React / Vue	新專案、大公司、高薪
jQuery	舊專案、中小公司、初級 PHP 求職門檻
全世界 75% 網站還在用
90% 初級 PHP 職缺要求 jQuery
WordPress 內建 jQuery
2. 引入 jQuery（CDN）
HTML

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="script.js"></script>   <!-- jQuery 一定要先載入 -->
3. $ 是什麼
$ = jQuery 的全域變數，等同於 jQuery，業界 99% 用 $。

4. 選取（對比 Week2）
原生 JS	jQuery
document.querySelector("#id")	$("#id")
document.querySelectorAll(".class")	$(".class")
jQuery 的 $() 自動處理單個 / 多個，回傳同樣的物件。

5. 常用方法
操作	jQuery
改文字	.text("新文字")
改 HTML	.html("<b>粗體</b>")
改樣式	.css("color", "red") 或 .css({color: "red"})
加 class	.addClass("active")
移除 class	.removeClass("active")
切換 class	.toggleClass("active")
監聽事件	.on("click", () => {...}) 或 .click(...)
6. 鏈式呼叫（Method Chaining）⭐
JavaScript

$("#title").text("Hi").css("color", "blue").addClass("active");
每個方法回傳 jQuery 物件本身 → 可以一直串。
這是 jQuery 最大的優勢。

💻 今日完成的程式碼
JavaScript

let count = 0;
const $message = $(".message");   // 用 $ 前綴 + 存起來

$("#myBtn").on("click", () => {
    count++;
    console.log(`點擊次數: ${count}`);
    $message.text(`被點擊了 ${count} 次`)
            .css("color", count % 2 === 0 ? "red" : "blue");
});
🧠 學到的工作思維
新專案學新技術，舊專案用 jQuery —— 兩個都要會
jQuery 物件用 $ 前綴命名 —— 業界慣例
重複的選取存起來 —— 效能 + 可讀性
.on() 比 .click() 通用 —— 工作上首選
需求是底線，不要擅自加功能 —— 工作習慣
📝 GitHub Commit Message 建議
text

week3-day1: jQuery 入門

- 引入 jQuery CDN
- $() 選取 + 鏈式呼叫
- .text() / .css() / .addClass() 基本操作
- .on("click", ...) 事件監聽
- 對比原生 JS：相同功能 code 量少一半
- 命名慣例：jQuery 物件用 $ 前綴
🚀 明天預告：Day2 — jQuery 進階
主題：DOM 操作 + 表單 + 動畫

.append() / .prepend() / .remove()（對應 Q3 Todo List 的動態增刪）
表單操作：.val()（取得 input 值）
動畫：.fadeIn() / .slideDown() / .animate()
.each()（jQuery 的 forEach）
明天會學到用 jQuery 重寫 Todo List 會超短的感覺 💪