let weight = 70; // 單位: kg
let height = 1.77; // 單位: m
let BMI = weight / (height * height);
let result;
if (BMI < 18.5) result = "過輕";
else if (18.5 <= BMI && BMI < 24) result = "正常";
else if (24 <= BMI && BMI < 27) result = "過重";
else result = "肥胖";

// BMI.toFixed(2); 取兩位小數，要有變量接受，且回傳的類型是string，無特別需求時要在顯示時才轉!!!
console.log(`你的 BMI 是 ${BMI.toFixed(2)}，分類: ${result}`)