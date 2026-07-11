📘 Week2 Day2 學習總結
🎯 今日主題
JavaScript 運算子 + 條件判斷

📚 學到的核心知識
1. 數學運算子
跟 C++ 幾乎一樣，但 JS 有兩個重點：

運算子	注意點
/	永遠回傳浮點數（C++ 整數除法會截斷，JS 不會）
%	取餘數，常用來判斷奇偶、表格隔行變色
簡寫：+= -= *= ++ --（跟 C++ 一樣）

2. 比較運算子（⚠ JS 最大坑）
運算子	行為	工作上
==	會自動轉型，有坑	❌ 不要用
===	嚴格比較，型別也要一樣	✅ 一律用這個
!= !==	同上	✅ 用 !==
鐵律：永遠用 === 和 !==。

3. 字串比較規則
JavaScript

"10" > 5      // true  → 字串轉數字
"10" > "9"    // false → 兩邊都字串，比 ASCII（"1" < "9"）
"abc" > "abd" // false → 逐字元比 ASCII
⚠ 從 input 拿到的永遠是字串，做數字比較一定要 Number() 轉型。

4. 邏輯運算子 + 短路求值
JavaScript

"hello" && "world"   // "world"  → 回傳值，不是 true
"hello" || "world"   // "hello"
null || "default"    // "default" → 設預設值的常用技巧
JS 不會回傳 true/false，會回傳實際的值。

5. Truthy / Falsy
Falsy（會被當 false）：

text

false  0  ""  null  undefined  NaN
其他全部 Truthy，包括：

"0"（字串「0」）
"false"（字串「false」）
[]（空陣列）
{}（空物件）
⚠ 跟 PHP 不一樣！PHP 的 [] 是 false。

6. 條件判斷
JavaScript

if / else if / else   // 跟 C++ 一樣
條件 ? A : B          // 三元運算子，超常用
JS 特色： 條件不一定要是 boolean，可以利用 truthy/falsy：

JavaScript

if (user) { ... }            // 檢查 user 存不存在
if (!errorMessage) { ... }   // 檢查沒有錯誤
💻 今日重點程式碼
JavaScript

// 比較運算子（鐵律：用 ===）
console.log(5 === "5");   // false
console.log(0 === false); // false

// 短路求值設預設值
let userName = inputName || "Guest";

// 短路執行
isLoggedIn && showDashboard();

// 成績判斷（邏輯與輸出分離）
let grade;
if (score >= 90) grade = "A";
else if (score >= 80) grade = "B";
else if (score >= 60) grade = "C";
else grade = "F";
console.log(`你的成績是 ${grade}`);

// 三元運算子
let status = age >= 18 ? "可以投票" : "未成年";
⚠️ 今天踩過的坑（重要！）
坑 1：忘記寫 let
JavaScript

score = 90;   // ❌ 變成全域變數
let score = 90;   // ✅
教訓： JS 不會報錯，但會偷偷掛全域，工作上會被退件。

坑 2：分號不一致
JavaScript

console.log("a");   // ✅ 有分號
console.log("b")    // ❌ 沒分號
教訓： 要嘛全加，要嘛全不加，不能混。建議全加。

坑 3：重複的 console.log
JavaScript

// ❌ 不好
if (...) console.log("你的成績是 A");
else if (...) console.log("你的成績是 B");

// ✅ 好：邏輯與輸出分離
let grade;
if (...) grade = "A";
console.log(`你的成績是 ${grade}`);
教訓： 重複的字串/邏輯 → 抽出來。改一處而不是改 N 處。

🧠 學到的工作思維
變數職責單一 → 不要中途換用途
邏輯與輸出分離 → 易讀、易維護、易改寫
註解寫「為什麼」，不要寫「做什麼」 → code 自己會說它在做什麼
JS 是弱型別，所以更要嚴格 → ===、明確轉型、寫分號
📝 GitHub Commit Message 建議
text

week2-day2: JS 運算子 + 條件判斷

- 數學運算子（+ - * / % **）
- 比較運算子（=== vs == 坑）
- 邏輯運算子 + 短路求值
- Truthy / Falsy 觀念
- if / else if / else + 三元運算子
- Code Review：let 漏寫、分號一致性、邏輯與輸出分離
🚀 明天預告：Day3 — 練習日 💪
重要：明天不教新東西，全部用今天 + Day1 學的做小練習。

預計練習：

寫一個「BMI 計算器」（純 console，明天先不碰網頁）
寫一個「FizzBuzz」經典題（檢查迴圈前的判斷功力）
一個小挑戰題
⚠ 明天規則：不准看舊 code，靠自己想。
卡住先想 20 分鐘，再來問我。

