const title = document.querySelector("#title"); // 選取 id 加入 #，與 CSS 一致
console.log(title);
const message = document.querySelector(".message");
console.log(message);
const myBtn = document.querySelector("#myBtn");
console.log(myBtn);

console.log(typeof title);
console.log(typeof message);
console.log(typeof myBtn);

setTimeout(() => title.textContent = "譚海超THC", 1500);

setTimeout(() => {
    message.style.color = "blue";
    message.style.fontSize = "24px";
}, 2500);

setTimeout(() => myBtn.classList.add("highlight"), 3500);

let count = 0;
myBtn.addEventListener("click", () => {
    console.log("Hello"); // Q1: 點擊按鈕， console 打印 Hello。
    count++; // 統計點擊次數
    title.textContent = `被點擊了 ${count} 次`; // Q2和Q4: 點擊按鈕，改變 title 內容。
    myBtn.classList.toggle("highlight"); // Q3: 點擊按鈕，切換按鈕樣式。 toggle: 已有類名就刪，無就加。
});