let target = Math.floor(Math.random() * 100) + 1;
let frequency = 0;
let maxNum = 100;
let minNum = 1;
const screenContent = document.querySelector(".screenContent");
const hint = document.querySelector(".hint");
const frequencyContent = document.querySelector(".frequencyContent");
const input = document.querySelector("input");
const guessBtn = document.querySelector(".guessBtn");
const updateDisplay = () => {
    hint.textContent = `${minNum} ~ ${maxNum}`;
    frequencyContent.textContent = `次數: ${frequency}`;
}
guessBtn.addEventListener("click", () => {
    
    const value = input.value;
    const num = Number(value);
    if (value === "" || isNaN(num)) {
        screenContent.textContent = "請輸入有效數字";
        return;
    }
    if (num < 1 || num > 100) {
        screenContent.textContent = "請輸入 1 ~ 100";
        return;
    }
    frequency++;
    if (num > target) {
        maxNum = num;
        screenContent.textContent = "大了! 再試試";
        updateDisplay();
    }
    else if (num < target) {
        minNum = num;
        screenContent.textContent = "小了! 再試試";
        updateDisplay();
    }
    else {
        screenContent.textContent = `答對了! 共猜了 ${frequency} 次`;
        hint.textContent = `答案是 ${target}`;
        frequencyContent.textContent = `次數: ${frequency}`;
        input.value = "";
        guessBtn.disabled = true;
    }
});


const restartBtn = document.querySelector(".restartBtn");
restartBtn.addEventListener("click", () => {
    target = Math.floor(Math.random() * 100) + 1;
    maxNum = 100;
    minNum = 1;
    frequency = 0;
    screenContent.textContent = "開始遊戲";
    hint.textContent = "1 ~ 100";
    frequencyContent.textContent = "次數: 0"
    input.value = "";
    guessBtn.disabled = false;
});
