for (let i = 1; i <= 30; i++) {
    let result;
    if ((i % 15) === 0) result = "FizzBuzz";
    else if ((i % 5) === 0) result = "Buzz";
    else if ((i % 3) === 0) result = "Fizz";
    else result = i;
    console.log(result);
}