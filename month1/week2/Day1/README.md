📘 Week2 Day1 學習總結
🎯 今日主題
JavaScript 入門：環境設定、變數、資料型別

📚 學到的核心知識
1. JS 怎麼引入 HTML
HTML

<script src="script.js"></script>
通常放在 </body> 前面
src 指向 JS 檔案路徑
載入後 JS 自動執行
2. console.log() — debug 大法
JavaScript

console.log("JS is working");
把訊息印到瀏覽器 Console
工作上 90% 的 debug 都靠它
開啟方式：右鍵 → 檢查 → Console
3. 變數宣告：let vs const
關鍵字	用途	可否重新賦值
let	一般變數	✅ 可以
const	常數	❌ 不可以
var	舊寫法	⚠ 已淘汰，不要用
JavaScript

let userName = "THC";
const city = "Macao";

userName = "Thomas"; // ✅ OK
city = "HongKong";   // ❌ TypeError
4. 命名規則
✅ 駝峰式：userName、isStudent
❌ 不能用數字開頭：1name
❌ 不能用底線習慣：user_name（JS 不用這個）
❌ 不能用保留字：let、function
5. 基本資料型別
型別	範例
String	"hello"、'abc'
Number	22、3.14
Boolean	true / false
Undefined	宣告了沒給值
Null	故意設為「空」
查型別用 typeof：

JavaScript

typeof "hello"  // "string"
typeof 22       // "number"
typeof true     // "boolean"
6. 字串拼接的兩種寫法
方法 1：用 + 拼接（舊寫法）

JavaScript

console.log("我叫" + userName + "，今年" + age + "歲");
方法 2：Template Literals 模板字串（推薦 ✅）

JavaScript

console.log(`我叫${userName}，今年${age}歲`);
使用 反引號 `（不是雙引號！）
變數用 ${變數名} 包起來
可以換行、可讀性高
💻 今日完成的程式碼
JavaScript

// 變數
let userName = "譚海超";
let age = 22;
const city = "Macao";

// 資料型別
console.log(typeof "hello");  // string
console.log(typeof 12);       // number
console.log(typeof true);     // boolean

// 自我介紹
console.log(`大家好，我叫${userName}，今年${age}歲，住在${city}。`);
⚠️ 今天踩過的坑（重要！）
坑 1：Template Literals 用錯引號
JavaScript

❌ console.log("大家好，我叫`${userName}");
   // 用雙引號包住，${} 完全不會被解析，會印出純文字

✅ console.log(`大家好，我叫${userName}`);
   // 必須整個用反引號包起來
教訓： 反引號 ` 和雙引號 " 是兩個不同的東西，不能混用。

坑 2：報錯會中斷整支程式
JavaScript

city = "HongKong";   // ❌ 報錯
console.log(city);   // 這行不會執行
教訓： 看到 Console 紅色錯誤，先修錯，後面 code 都會被擋住。

🧩 JS 的兩個惡名昭彰的坑
坑 1：typeof null 回傳 "object"
JavaScript

typeof null  // "object" ← 這是 JS 的歷史 bug
null 明明是空值，卻被歸類為 object，這是 20 幾年的歷史問題，永遠不會修。

坑 2：隱式型別轉換（Type Coercion）
JavaScript

"5" + 3   // "53"  → 字串拼接
"5" - 3   // 2     → 自動轉成數字
+ 看到字串 → 全部當字串接起來
- * / 沒辦法用在字串 → JS 偷偷轉成數字
未來會學到： 用 === 而不是 == 來避免這種坑。

🧠 學到的工作思維
看到錯誤訊息不要怕

90% 的錯誤訊息直接告訴你錯在哪
例：Assignment to constant variable = 你改了 const
主動踩坑也是學習

故意改 const、故意亂打，看會發生什麼
比死背規則更有效
註解 = 未來的自己

把踩坑經驗寫在 code 旁邊
複習時一秒回想
📝 GitHub Commit Message 建議
text

week2-day1: JS 基礎入門

- 學會 <script> 引入 JS
- 練習 let / const 差別
- 認識 5 種基本資料型別 + typeof
- 字串拼接：+ 與 Template Literals
- 踩坑記錄：typeof null、隱式轉型、報錯中斷
🚀 明天預告：Day2 — 運算子 + 條件判斷
數學運算子（+ - * / %）
比較運算子（=== vs == 重要！）
邏輯運算子（&& || !）
if / else / else if
三元運算子（? :）
明天會比較硬，但學完就能寫「會判斷的程式」💪