📘 Week3 Day6 學習總結
🎯 今日主題
綜合練習：整合 AJAX + Tab + Accordion + Modal 做產品詳情頁

📚 學到的新知識
1. z-index：控制元素堆疊順序
用途	建議值
一般元素	1~10
Header/Sidebar	100
Tooltip	999
Modal	9999
緊急通知	99999
規則： Modal 一定要 z-index，不然會被 position: absolute 元素蓋過。

2. 事件委派（Event Delegation）⭐
問題： AJAX 後動態生成的元素無法用 $("#xxx").on() 綁事件。

解決：

JavaScript

$(document).on("click", "#xxx", handler);
//   ↑ 綁在父元素          ↑ 但只對這個目標觸發
優點：

對「還沒存在」的元素也有效
動態加入的元素自動有事件
這是 jQuery 生存的最重要理由之一。

3. 綁定事件的位置策略
事件類型	綁在哪
一次性設定（關閉、提交）	外面（只綁一次）
每次觸發要更新（計數、狀態）	觸發器的 handler 內
別把「一次性綁定」放在會重複執行的 handler 裡 —— 會累積綁多次。

💻 今日程式碼精華
JavaScript

// AJAX 抓資料
$.get(API).done((data) => {
    $("#product").html(組好的 HTML);
});

// Tab（憑記憶寫出 Day5 的邏輯）
$(".tab").on("click", function() {
    const tabNum = $(this).data("tab");
    $(".tab").removeClass("active");
    $(this).addClass("active");
    $(".content").removeClass("active");
    $(`.content[data-content="${tabNum}"]`).addClass("active");
});

// Accordion（憑記憶）
$(".question").on("click", function() {
    const $currentAnswer = $(this).next(".answer");
    const isOpen = $currentAnswer.is(":visible");
    $(".answer").slideUp(400);
    $(".arrow").text("▼");
    if (!isOpen) {
        $currentAnswer.slideDown(400);
        $(this).find(".arrow").text("▲");
    }
});

// Modal + 事件委派
let count = 0;
$(".modal-overlay").on("click", (e) => {
    if (e.target === e.currentTarget) removeModal();
});
$(".modal-close, .modal-ok").on("click", removeModal);

$(document).on("click", "#cartBtn", () => {
    count++;
    $(".modal-box").find("span").text(count);
    $(".modal-overlay").addClass("active");
});
⚠️ 今天踩過的坑
坑 1：動態元素綁不到事件
解法： 事件委派 $(document).on("click", "選擇器", ...)

坑 2：Modal 被 position: absolute 元素蓋掉
解法： z-index: 9999

坑 3：關閉事件綁在每次 handler 裡 → 累積多次
解法： 綁在外面，只綁一次

坑 4：id 和 class 混用（id="modal-close" vs .modal-close）
教訓： HTML 和 CSS/JS 的選擇器要一致

🧠 學到的工作思維
分階段開發 —— 一個功能一個功能加，別一次寫完
CSS 留最後 —— 功能優先
看到 bug 先 debug 觀念（z-index 問題）
事件的「一次性」vs「每次觸發」要分清楚
事件委派是綁動態元素的標準做法