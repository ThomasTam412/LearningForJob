$(".question").on("click", function() {
    const $currentAnswer = $(this).next(".answer");
    const isOpen = $currentAnswer.is(":visible");

    $(".answer").slideUp(300);
    $(".arrow").text("▼")

    if (!isOpen) {
        $(this).find(".arrow").text("▲");
        $currentAnswer.slideDown(300);
    }
});