// 循環部分
console.log("===== 循環部分 =====");
// Q1
console.log("Q1");
let i = 1;
while (i <= 5) {
    console.log(i);
    i++;
}

// Q2
console.log("Q2");
let dataSet = [3, 7, 12, 18, 25];
for (let j = 0; j < dataSet.length; j++) {
    if (dataSet[j] > 10) {
        console.log(`找到了: ${dataSet[j]}`);
        break;
    }
}

// Q3
console.log("Q3");
for (let k = 1; k <= 10; k++) {
    if (k % 2 === 1) {
        console.log(k);
    }
}

// 函數部分
console.log("===== 函數部分 =====");
// Q1
function add(a, b) {
    return a + b;
}
console.log("Q1");
console.log(add(3, 5));
console.log(add(10, 20));

// Q2
/*
function getGrade(score){
    if (score >= 90) return "A";
    else if (score >= 80) return "B";
    else if (score >= 60) return "C";
    else return "F";
}
console.log("Q2");
console.log(getGrade(95));
console.log(getGrade(85));
console.log(getGrade(70));
console.log(getGrade(40));
*/
console.log("這部分 Q2 被隱藏")

// Q3
function isEven(num) {
    return num % 2 === 0;
}
console.log("Q3");
console.log(isEven(4));
console.log(isEven(7));
console.log(isEven(0));

// 函數細節
console.log("===== 函數細節 =====");
// Q1
console.log("Q1");
const getGrade = (score) => {
    if (score >= 90) return "A";
    else if (score >= 80) return "B";
    else if (score >= 60) return "C";
    else return "F";
}
console.log(getGrade(85));

// Q2
console.log("Q3");
const square = x => x * x;
console.log(square(5));
console.log(square(10));

// Q3
console.log("Q3");
const greet = (name = "Guest", greeting = "Hello") => {
    console.log(`${greeting}, ${name}!`)
}
greet();
greet("Thomas");
greet("Thomas", "Hi");
greet(undefined, "Hi")