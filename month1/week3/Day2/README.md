📘 Week3 Day2 學習總結
🎯 今日主題
jQuery 進階：DOM 增刪、表單、動畫、遍歷

📚 學到的核心知識
1. DOM 增刪
方法	作用
.append(html)	加到內部最後
.prepend(html)	加到內部最前
.after(html)	加到自己之後
.before(html)	加到自己之前
.remove()	移除自己
.empty()	清空子元素
安全做法： $("<li></li>").text(userInput) 避免 XSS

2. 表單操作
方法	用途	範例
.val()	取 input 值	$("#input").val()
.val("xxx")	設 input 值	$("#input").val("hi")
.prop()	控制屬性	$("#btn").prop("disabled", true)
鐵律： disabled / checked / selected → 永遠用 .prop()，不要用 .attr()

3. 動畫效果 ⭐
方法	效果
.fadeIn(時間) / .fadeOut(時間)	淡入淡出
.fadeToggle(時間)	切換淡入淡出
.slideDown(時間) / .slideUp(時間)	滑動
.slideToggle(時間)	切換滑動
.animate({...}, 時間)	自訂動畫
.hide() / .show()	立刻隱藏/顯示
.animate() 只能改數值類（width、opacity 等），
顏色要用 .css() 配合改。

4. .each() 遍歷
JavaScript

$(".item").each(function(i) {
    $(this).text(`項目 ${i}`);
});
⚠ 必須用 function()，不能用箭頭函式（因為 this）

💻 今日完成的程式碼亮點
JavaScript

const colors = ["red", "blue", "green", "yellow"];   // 常數放外面

$("#addBtn").on("click", () => {
    count++;
    $("#list").append($("<li></li>").addClass("item").text(`新項目 ${count}`));
    
    $(".item").each(function(i) {
        $(this).css("background-color", colors[i % 4]);   // % 循環上色
    });
});
⚠️ 今天踩過的坑
坑 1：$(...) 物件不能直接 .trim()
JavaScript

❌ $("#input").trim()
✅ $("#input").val().trim()
教訓： 不同類型物件有不同方法。jQuery 物件要先取出值才能用字串方法。

坑 2：.attr("disabled") vs .prop("disabled")
JavaScript

❌ $("#btn").attr("disabled")    // 回傳字串
✅ $("#btn").prop("disabled")    // 回傳 true/false
坑 3：邏輯結構混亂（Early return 沒用）
JavaScript

❌ if 裡只放 alert，後面照樣執行清空
✅ if 不通過 → return；通過才執行剩下的
坑 4：.each() 用箭頭函式
JavaScript

❌ $(".item").each((i) => { $(this).text(...) })   // this 是錯的
✅ $(".item").each(function(i) { $(this).text(...) })
🧠 學到的工作思維
編號和「目前數量」是兩件事 —— Gmail / 訂單編號的邏輯
安全做法預設使用 —— .text(input) 而不是 ${input} 拼字串
常數放外面 —— 不變的資料只建立一次
整合到自然觸發點 —— 你把上色放進新增，不需要獨立按鈕
Early return 結構 —— 驗證失敗立刻跳出
📝 GitHub Commit Message 建議
text

week3-day2: jQuery 進階 - DOM 增刪、表單、動畫、遍歷

- .append / .remove / .empty
- .val() / .prop() 表單操作
- .fadeToggle / .slideToggle / .animate 動畫
- .each() 遍歷 + this 指向
- XSS 安全：$("<li></li>").text(input)
- 主動優化：上色整合到新增動作、常數提到外面
🚀 明天預告：Day3 — 練習日 💪
明天用 jQuery 重寫 Week2 Day6 的某個練習，
你會親自體會 「同樣的東西，jQuery 寫起來短一半」 的感覺。

預計題目（會挑 1~2 個）：

用 jQuery 重寫計數器 ⭐
用 jQuery 重寫Todo List（學會這個就解鎖 jQuery 的精髓）
用 jQuery 重寫主題切換
⚠ 明天規則：不准看 Week2 舊 code，憑記憶 + Day1/Day2 所學重寫。