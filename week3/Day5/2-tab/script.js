$(".tab").on("click", function () {
    const tabNum = $(this).data("tab");
    $(".tab").removeClass("active");
    $(this).addClass("active");
    $(".content").removeClass("active");
    $(`.content[data-content="${tabNum}"]`).addClass("active");
});