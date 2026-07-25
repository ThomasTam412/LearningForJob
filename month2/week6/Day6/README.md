📘 Week 6 Day 6 學習筆記
主題：MVC 初步
🎯 今日產出
text

week6/Day6/
├── db.php                   ← Session + PDO + Flash
├── Flash.php
├── Todo.php                 ← Domain model
├── TodoList.php             ← Collection
├── TodoRepository.php       ← Data access (Model)
├── TodoController.php       ← 今日核心
├── views/
│   └── todo.view.php        ← 純 HTML (View)
└── todo_MVC.php             ← Entry point (5 行)
🧠 核心概念
MVC 分層
Layer	職責	你 Week 6 對應檔案
Model	數據 + 業務邏輯	Todo, TodoList, TodoRepository
View	HTML 渲染	views/todo.view.php
Controller	收 request，協調 Model + View	TodoController
關注點分離（Separation of Concerns） — OOP 四大特性之一。

Front Controller Pattern
所有 request 由一個 entry point 進入：

PHP

<?php
require_once "db.php";
require_once "TodoController.php";

$controller = new TodoController(
    new TodoRepository($pdo),
    new Flash(),
);

$controller->handle();
Laravel 嘅 public/index.php 就係咁。

🎯 今日 3 個新 OOP 概念
1. Multiple Constructor Property Promotion
PHP

public function __construct(
    private TodoRepository $repo,
    private Flash $flash,
) {}
一句 constructor 收多個 dependencies + 自動變 $this->xxx。

2. private method
PHP

private function handlePost(): void { ... }
private function showList(): void { ... }
封裝（Encapsulation） — 強制外面用 handle() public API 入口。

3. require 帶變數入 view
PHP

private function showList(): void {
    $todos = $this->repo->findAll();
    $total = $todos->count();
    require "views/todo.view.php";   // view 攞到 $todos, $total
}
PHP require = 「將檔案 copy paste 到當前位置執行」，冇獨立 scope。

🎬 Request → Response 完整 Flow
text

用戶開瀏覽器 → GET todo_MVC.php
    ↓
todo_MVC.php 執行：
    - Include db.php (建立 $pdo)
    - Include TodoController.php (定義 class)
    - new TodoController(new TodoRepository($pdo), new Flash())
    - $controller->handle()
    ↓
handle() 判斷 GET → 呼叫 showList()
    ↓
showList()：
    - $todos = $this->repo->findAll()
    - 準備變數
    - require view
    ↓
View render HTML → output
    ↓
瀏覽器顯示頁面 ✅
🐛 今日踩過嘅坑 / 有留意到嘅嘢
1. View 檔案報 warning
VS Code 唔知道 $todos, $total 邊度嚟 → 報 warning
真相： Runtime 冇 error，因為 require 會帶變數入 scope

2. Method 順序 + private
一開始 handle() 排最尾、method 全 public
Refactor： handle() 上頂、helper method 改 private

3. "唔知點解會 work"
最重要嘅自我察覺 —— 呢個係學新 pattern 嘅正常階段

💡 今日感受檢查（Answer: C）
「暫時 OK 但要 lookup cheatsheet」 + 「唔知點解會 work」

呢個狀態係健康嘅：

你 code 寫得出 ✅
你感受到 work ✅
你承認「唔明」 ✅ ← 呢個最重要
你需要嘅係「用得多，自然明」，唔係「一定要 100% 明先繼續」。

3 個月後你會發現 MVC 變條件反射。

📊 Week 6 進度
Day	主題	狀態
Day 1	OOP 入門 + Flash class	✅
Day 2	Todo + TodoList (composition)	✅
Day 3	練習日 (User + UserList)	✅
Day 4	Cheatsheet Day	✅
Day 5	Repository Pattern	✅
Day 6	MVC 初步	✅
Day 7	休息 / 買電腦	🌙