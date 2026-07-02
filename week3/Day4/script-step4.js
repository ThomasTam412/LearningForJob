$("#loadBtn").on("click", () => {
    $("#loadBtn").prop("disabled", true);
    $("#result").html(`載入中...`);
    $.get("https://jsonplaceholder.typicode.com/users")
        .done((users) => {
            $("#result").html("");
            let allCardHtml = "";
            $.each(users, (index, user) => {
                allCardHtml += `
                    <div class="card">
                        <h3>${user.name}</h3>
                        <p>📧 ${user.email}</p>
                        <p>🏢 ${user.company.name}</p>
                        <p>🌆 ${user.address.city}</p>
                    </div>
                `;
            });
            $("#result").append(allCardHtml);
        })
        .fail((error) => {
            $("#result").html(`<p class="error-msg">載入失敗：${error.status} ${error.statusText}</p>`)
        })
        .always(() => {$("#loadBtn").prop("disabled", false)});
});