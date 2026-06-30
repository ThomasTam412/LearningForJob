console.log($);
let count = 0;
const colors = ["red", "blue", "green", "yellow"];
$("#addBtn").on("click", () => {
    count++;
    $("#list").append($("<li></li>").addClass("item").text(`新項目 ${count}`));
    $(".item").each(function(i) {
        
        $(this).css("background-color", colors[i % 4]);
    });
});
$("#removeBtn").on("click", () => {
    $("#list li:last-child").remove();
});

$("#getValBtn").on("click", () => {
    const value = $("#myInput").val();
    if (value.trim() === "") {
        alert("請輸入內容")
        return;
    }
    alert(value);
    $("#getValBtn").prop("disabled", true);
    $("#myInput").val("");
    setTimeout(() => $("#getValBtn").prop("disabled", false), 3000);
});

$("#fadeBtn").on("click", () => {
    $("#myBox").fadeToggle(500);
});
$("#slideBtn").on("click", () => {
    $("#myBox").slideToggle(500);
});
$("#customBtn").on("click",() => {
    $("#myBox").css("background-color", "red").animate({ width: "400px", height: "400px"}, 1000);
});