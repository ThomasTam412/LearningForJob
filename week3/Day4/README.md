📘 Week3 Day4 學習總結
🎯 今日主題
AJAX + API：讓網頁能跟後端說話

📚 學到的核心知識
1. 概念
API = 系統之間對話的接口
Web API / REST API = 前端呼叫後端的接口
JSON = API 資料的通用格式
AJAX = 網頁不重整就能跟伺服器要資料
非同步 = JS 不等資料回來，繼續往下跑
2. jQuery AJAX 三大方法
方法	用途
$.get(url, callback)	拿資料
$.post(url, data, callback)	送資料
$.ajax({...})	完整版
3. 完整寫法（工作標準）
JavaScript

$.get(url)
    .done((data) => { /* 成功 */ })
    .fail((error) => { /* 失敗 */ })
    .always(() => { /* 不管成功失敗都執行 */ });
4. $.each() vs $(selector).each()
方法	用途
$.each(陣列, callback)	遍歷任何陣列/物件
$(selector).each(callback)	遍歷選到的 DOM 元素
5. 完整 AJAX 流程
text

1. 禁用按鈕 + 顯示 loading
2. 發送 AJAX
3. done → 隱藏 loading + 顯示資料
4. fail → 隱藏 loading + 顯示錯誤
5. always → 解禁按鈕
💻 今日完成的程式碼亮點
JavaScript

$("#loadBtn").on("click", () => {
    $("#loadBtn").prop("disabled", true);
    $("#result").html("載入中...");
    
    $.get("https://jsonplaceholder.typicode.com/users")
        .done((users) => {
            $("#result").html("");
            $.each(users, (index, user) => {
                const cardHtml = `
                    <div class="card">
                        <h3>${user.name}</h3>
                        <p>📧 ${user.email}</p>
                        <p>🏢 ${user.company.name}</p>
                        <p>🌆 ${user.address.city}</p>
                    </div>
                `;
                $("#result").append(cardHtml);
            });
        })
        .fail((error) => {
            $("#result").html(`<p class="error-msg">載入失敗：${error.status} ${error.statusText}</p>`);
        })
        .always(() => {
            $("#loadBtn").prop("disabled", false);
        });
});
⚠️ 今天踩過的坑
坑 1：.appendTo() 累積不清空
每次 AJAX 前要 .empty() 或用 .html() 覆蓋。

坑 2：把 console.log("3") 印在 AJAX 後面 → 卻先執行
非同步的本質： JS 不等資料，會繼續往下跑。
規則： 需要用 data 的所有操作，都要放進 callback 裡。

坑 3：迴圈裡 .append() × N 次 → DOM 效能差
優化： 先組字串，一次 .html()。

🧠 學到的工作思維
AJAX 一定要有錯誤處理 —— 網路不是永遠可靠的
Loading 提示不能省 —— 使用者需要反饋
按鈕載入時禁用 —— 避免重複送出
非同步思維 —— 資料相關的事都要放 callback
DOM 操作能少就少 —— 記憶體裡組好再寫入
📝 GitHub Commit Message 建議
text

week3-day4: AJAX + API 入門

- $.get() 呼叫 JSONPlaceholder API
- .done() / .fail() / .always() 完整三段式
- $.each() 遍歷 API 回傳的陣列
- 動態產生使用者卡片列表
- Loading / Error / 按鈕禁用完整流程
- 觀念：非同步、JSON、REST API、DOM 效能優化
🎓 里程碑：你今天正式踏入 Web 工程的門
Week1：你能寫「靜態網頁」
Week2：你能寫「有互動的網頁」
Week3 Day1~3：你能用 jQuery 快速開發
Week3 Day4（今天）：你能寫「跟後端對話的網頁」⭐

未來 Week4 開始你會學 PHP —— 那時候你就自己寫後端，
自己的 JS 呼叫自己寫的 PHP API。

這就是完整的「Full Stack」開發流程。

🚀 明天預告：Day5 — 真實 UI 元件
主題：Modal 彈窗 / Tab 分頁 / 手風琴

明天做的是每個網站都有的元件：

點按鈕彈出視窗（Modal）
分頁切換內容（Tab）
收合展開內容（Accordion）
這些是面試常考的實作題，做完你會發現「原來我常看到的功能是這樣寫的」的爽感 ⭐