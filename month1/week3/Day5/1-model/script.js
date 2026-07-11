const $overlay = $(".modal-overlay");
const $openBtn = $("#openBtn");
const $closeBtn = $(".modal-close");
const $okBtn = $(".modal-ok");

$openBtn.on("click", () => {
    $overlay.addClass("active");
});

const closeModal = () => $overlay.removeClass("active");

$closeBtn.on("click",closeModal);
$okBtn.on("click", closeModal);
$overlay.on("click", (e) => {
    if (e.target === e.currentTarget) closeModal();
});