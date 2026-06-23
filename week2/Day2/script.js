// 運算子
let a = 10;
let b = 3;
console.log(a + b);
console.log(a - b);
console.log(a * b);
console.log(a / b); // 是不整的，即3.333...
console.log(a % b); // 10 除以 3 取余即 1
let score = 60;
score += 20; // 相當於 score = score + 20 
console.log(score);

// 比較運算子
console.log(5 == "5"); // true <- String被轉成Number
console.log(5 === "5"); // false 類型不同
console.log(0 == false); // true <- false 被當作 0
console.log(0 === false);
console.log(null == undefined); // ture
console.log(null === undefined);
console.log("10" > 5); // 有一邊數字就全轉為數字
console.log("abc" > "abd");

// 邏輯運算子
console.log(true && false);
console.log(true || false);
console.log(!true);
console.log("hello" && "world");
console.log("" && "world");
console.log("hello" || "world");
console.log(null || "default value");
console.log(Boolean(0));
console.log(Boolean(""));
console.log(Boolean("0"));      // 注意！
console.log(Boolean([]));       // 注意！

// 條件判斷
let testScore = 90;
let grade;
if (testScore >= 90) grade = "A";
else if (testSfcore >= 80) grade = "B";
else if (testScore >= 60) grade = "C";
else grade = "F";
console.log(`你的成績是 ${grade}`);

let age = 17;
let isAdult = age >= 18 ? "可以投票" : "未成年";
console.log(isAdult);

