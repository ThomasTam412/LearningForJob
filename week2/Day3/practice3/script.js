let scores = [85, 72, 90, 45, 67, 88, 92, 55, 78, 60];
let total = scores.length;
let maxScore = 0;
let minScore = 100;
let sum = 0;
let level = "";
let pass = 0;
let fail = 0;

for (let i = 0; i < total; i++) {
    if (scores[i] > maxScore) maxScore = scores[i];
    if (scores[i] < minScore) minScore = scores[i];
    if(scores[i] < 60) {
        level = "F";
        fail += 1;
    }
    else {
        if (scores[i] >= 90) level = "A";
        else if (scores[i] >= 80) level = "B";
        else level = "C";
        pass += 1;
    }
    console.log(`學生 ${i + 1}: ${scores[i]}，等級 ${level}`)
    sum += scores[i];
}
let average = sum / total;
console.log("====== 統計結果 ======");
console.log(`總人數: ${total}`);
console.log(`平均分: ${average.toFixed(2)}`);
console.log(`最高分: ${maxScore}`);
console.log(`最低分: ${minScore}`);
console.log(`及格人數: ${pass}`);
console.log(`不及格人數: ${fail}`);

