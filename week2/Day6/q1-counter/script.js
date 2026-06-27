let count = 0;
const counter = document.querySelector(".counter");
const minus = document.querySelector("#minus");
const reset = document.querySelector("#reset");
const plus = document.querySelector("#plus");

const updateDisplay = () => {
    counter.textContent = `當前數字: ${count}`;
    if (count < 0) counter.style.color = "#FF4D4D";
    else if (count > 0) counter.style.color = "#B0D452";
    else counter.style.color = "white";
}

minus.addEventListener("click", () => {
    count--;
    updateDisplay();
    console.log(count); // 用於驗證是否正常工作，用於 debug
});

reset.addEventListener("click", () => {
    count = 0;
    updateDisplay();
    console.log(count);
});

plus.addEventListener("click", () => {
    count++;
    updateDisplay();
    console.log(count); // 用於驗證是否正常工作，用於 debug
});

