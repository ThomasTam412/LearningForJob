// AJAX
$.get("https://fakestoreapi.com/products/1")
    .done((data) => {
        const html = `
            <img src="${data.image}" alt="${data.title}">
            <div class="info-box">
                <h2>名稱: ${data.title}</h2>
                <p>價格: $${data.price}</p>
                <p>描述: ${data.description}</p>
                <p>評分: ⭐ ${data.rating.rate} / 5(${data.rating.count} 則評論)</p>
                <button id="cartBtn">加入購物車</button>
            </div>
        `;
        $("#product").html(html);
    })
    .fail((error) => {$("#product").html(`<p>載入失敗: ${error.status} ${error.statusText}</p>`)});

// Tab
$(".tab").on("click", function() {
    const tabNum = $(this).data("tab");
    $(".tab").removeClass("active");
    $(this).addClass("active");
    $(".content").removeClass("active");
    $(`.content[data-content="${tabNum}"]`).addClass("active");
});

// Accordion
$(".question").on("click", function() {
    const $currentAnswer = $(this).next(".answer");
    const isOpen = $currentAnswer.is(":visible");
    $(".answer").slideUp(400);
    $(".arrow").text("▼");
    if (!isOpen) {
        $currentAnswer.slideDown(400);
        $(this).find(".arrow").text("▲");
    }
});

// Modal
let count = 0;
const removeModal= () => $(".modal-overlay").removeClass("active");
$(".modal-overlay").on("click", (e) => {
        if (e.target === e.currentTarget) removeModal();
    });
$(".modal-close").on("click", removeModal);
$(".modal-ok").on("click", removeModal);
$(document).on("click", "#cartBtn", () => {
    count++;
    $(".modal-box").find("span").text(count);
    $(".modal-overlay").addClass("active");
});