const $input = $("#myInput");
const $addBtn = $("#addBtn");
const $list = $("#list");
const $number = $("#number");
let todos = [];
const renderNewTodo = () => {
    const newTodo = todos[todos.length - 1];
    const $newLi = $("<li></li>").text(newTodo);
    const $delBtn = $("<button></button>").text("刪除");
    
    $delBtn.appendTo($newLi);
    $newLi.hide().appendTo($list).fadeIn(300);
    $number.text(todos.length);
    
    $delBtn.on("click", function() {
        const $li = $(this).parent();
        const index = $li.index();
        $li.fadeOut(300, function() {
            $(this).remove();
            todos.splice(index, 1);
            $number.text(todos.length);
        });
    });
};

const addTodo = () => {
    const todo = $input.val();
    if (todo.trim() === "") return;
    todos.push(todo);
    $input.val("");
    renderNewTodo();
};

$addBtn.on("click", addTodo);

$input.on("keypress", (e) => {
    if (e.key === "Enter") addTodo();
});