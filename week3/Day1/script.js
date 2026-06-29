console.log($);

setTimeout(() =>$("#title").text("Hello jQuery!"), 3000);
setTimeout(() =>$("#title").text("這是一個計數器").css("color", "blue").css("backgroundColor", "yellow").css("fontSize", "20px"), 6000);

const $message = $(".message"); // 存起來，只找一次
let count = 0;
$("#myBtn").on("click", () => {
    count++;
    console.log(`點擊次數: ${count}`);
    $message.text(`被點擊了 ${count} 次`).css("color", count % 2 === 0 ? "red" : "blue");
});