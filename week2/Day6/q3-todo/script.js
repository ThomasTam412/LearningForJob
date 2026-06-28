const addBtn = document.querySelector("#addBtn");
const todoList = document.querySelector("#todoList");
const count = document.querySelector("#count");
const input = document.querySelector("input");

let todos = [];

const render = () => {
    todoList.innerHTML = "";
    for (let i = 0; i < todos.length; i++) {
        const li = document.createElement("li");
        const text = document.createElement("span");
        const deleteBtn = document.createElement("button");
        text.textContent = todos[i];
        deleteBtn.textContent = "刪除";
        li.appendChild(text);
        li.appendChild(deleteBtn);
        deleteBtn.addEventListener("click", () => {
            todos.splice(i, 1);
            render();
        });
        todoList.appendChild(li);
    }
    count.textContent = `總共 ${todos.length} 項`;
};

addBtn.addEventListener("click", () => {
    if (input.value.trim() === "") return;
    todos.push(input.value)
    input.value = "";
    render();
});

