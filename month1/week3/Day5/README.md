📘 Week3 Day5 學習總結
🎯 今日主題
真實 UI 元件：Modal / Tab / Accordion

📚 學到的核心知識
1. Modal 彈窗
HTML 結構： overlay + box 兩層
CSS： opacity + visibility 做動畫（避開 display: none 的坑）
JS： 3 個關閉觸發點 → 抽出 closeModal() 函式 DRY

新觀念：

事件冒泡：e.target === e.currentTarget 判斷點擊來源
2. Tab 分頁
HTML： data-tab="1" / data-content="1" 配對
CSS： position: absolute 讓 content 疊在同一位置
JS： class 切換 active 狀態

新觀念：

data-* 屬性 + jQuery .data() 讀取
屬性選擇器 [data-content="1"]
競態條件（Race Condition）：快速切換的動畫 bug
CSS transition 為什麼要配 visibility
3. Accordion 手風琴
HTML： question + answer 兩層
JS： slideUp / slideDown + 狀態統一重置

新方法：

.next() —— 找相鄰同層元素
.find() —— 找子元素
.is(":visible") —— 判斷顯示狀態
💻 今日程式碼精華
統一狀態切換模式（Accordion）
JavaScript

$(".question").on("click", function() {
    const $currentAnswer = $(this).next(".answer");
    const isOpen = $currentAnswer.is(":visible");

    // 先全部重置
    $(".answer").slideUp(300);
    $(".arrow").text("▼");

    // 如果自己原本收合，才展開自己
    if (!isOpen) {
        $(this).find(".arrow").text("▲");
        $currentAnswer.slideDown(300);
    }
});
這個模式（全部重置 → 只設定自己）在所有「切換式 UI」都通用。

⚠️ 今天踩過的坑
坑 1：display: none 和 flex 衝突
教訓： 現代做法用 opacity + visibility 取代 display。

坑 2：CSS transition 對 display 無效
教訓： transition 只能動「有數值變化」的屬性。

坑 3：Race Condition（快速點擊）
解法：

A. 動畫中禁用按鈕
B. .stop() 打斷舊動畫
C. 改用 CSS transition（推薦）
坑 4：三元運算子的無用判斷
JavaScript

if (!isOpen) {
    text(isOpen ? "▼" : "▲")   // isOpen 一定是 false，永遠 "▲"
}
教訓： if 內部已經知道條件，不用再判斷。

坑 5：狀態不同步（收合了 answer 但箭頭沒變）
教訓： 狀態要成對處理 —— 改一個就要改對應的其他。

🧠 學到的工作思維
元件化思維 —— 每個元件獨立資料夾
狀態管理模式 —— 全重置 → 設定當前
UX 敏感度 —— 「感覺卡」是要修的問題
迭代開發 —— 能動 → 有動畫 → 修 bug → 優化
成對處理 —— 開/關、加/移、顯/隱要一起考慮