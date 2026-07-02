/*
$("#loadBtn").on("click", () => {
    console.log("1. 準備發送請求")

    $.get("https://jsonplaceholder.typicode.com/users/1", (data) => {
        step1
        console.log(data);
        console.log(data.name);
        console.log(data.email);
        console.log(data.address.city);
        console.log(data.company.name);
       console.log("2. 資料到手了!", data.name);
    });

    console.log("3. 這行結束了")
});
*/
$("#loadBtn").on("click", () => {
    $("#loadBtn").prop("disabled", true); // 1.禁用按鈕
    $("#result").html("<p>載入中...</p>");
    $.get("https://jsonplaceholder.typicode.com/users/1")
        .done((data) => {
           $("#result").html(`
                <p>姓名: ${data.name}</p>
                <p>Email: ${data.email}</p>
                <p>公司: ${data.company.name}</p>
                <p>城市: ${data.address.city}</p>
            `);
        })
        .fail((error) => {
            $("#result").html(`<p>載入失敗：${error.status} ${error.statusText}</p>`);
        })
        .always(() => {
            $("#loadBtn").prop("disabled", false);
        });
});
