const $number = $("#number");
const $minus = $("#minus");
const $reset = $("#reset");
const $plus = $("#plus");

let count = 0;

const updateDisplay = () => {
    $(".btn").prop("disabled", true);
    $number.fadeOut(150, function() {
        $(this).text(count);
        if (count > 0) $(this).css("color", "#B0D452");
        else if (count < 0) $(this).css("color", "#FF4D4D");
        else $(this).css("color", "white");
        $number.fadeIn(150, function() {
            $(".btn").prop("disabled", false);
        });
    });
};
$minus.on("click", () => {
    count--;
    updateDisplay();
});
$plus.on("click", () => {
    count++;
    updateDisplay();
});
$reset.on("click", () => {
    count = 0;
    updateDisplay();
});