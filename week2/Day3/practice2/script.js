for (let i = 1; i <= 30; i++) {
    let result;
    if (i % 15 === 0) result = "FizzBuzz";
    else if (i % 5 === 0) result = "Buzz";
    else if (i % 3 === 0) result = "Fizz";
    else result = i;
    console.log(result);
}

/*
進階解法 
for (let i = 1; i <= 30; i++) {
    let result = "";
    if (i % 3 === 0) result += "Fizz"; 先判是不是 3 的倍數
    if (i % 5 === 0) result += "Buzz"; 再判是不是 5 的倍數
    console.log(result || i); // ""是 falsy，所以非特定倍數時會用 i。
}
*/
