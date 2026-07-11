// JS
console.log("JS is working");

// 變數(Variables)
let userName = "譚海超";
let age = 22;
const city = "Macao";
console.log(userName);
console.log(age);
console.log(city);
userName = "THC";
console.log(userName);

// 數據類型(Data Types)
console.log(typeof "hello");
console.log(typeof 12);
console.log(typeof true);
let x;
console.log(typeof x);
let y = null;
console.log(typeof y);
console.log(typeof null); // null 是「空值」，不是物件，但 typeof null 會回傳 "object"，這是歷史遺留問題。
console.log("5" + 3); // 結果: "53" 原因: 字串 + 任何東西 = 字串，這叫 字串拼接（string concatenation）。
console.log("5" - 3); // 結果: 2; 原因: 字串相減沒意義，所以 JS 會「偷偷把字串轉成數字」再相減。

// 小練習
let isSudent = true;
console.log("大家好，我叫" + userName + "，今年" + age + "歲，住在" + city + "，目前是學生。") // 字串 + 字串方式
console.log(`大家好，我叫${userName}，今年${age}歲，住在${city}，目前是學生。`) // 占位符方式: `字串...${變量}...`
// 注意事項
city = "HongKong"; // 正量無法修改，當 JS 遇到錯誤時，整支程式會中斷，後面的程式碼不會執行。
console.log(city);