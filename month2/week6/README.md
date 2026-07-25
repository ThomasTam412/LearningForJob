🏆 Week 6 大總結
主題：由「識寫功能」→「識用架構」
📈 Week 6 學習曲線
Week 5 尾狀態：

能寫完整登入系統
Code 結構「腳本式」（HTML 混 PHP、邏輯散落）
見到 $pdo->prepare() 反射寫
Week 6 尾狀態：

能將 code 分 layer（Model / View / Controller）
Domain code 完全冇 SQL
Entry point 只有 5 行
UI 檔案唔知道 DB 存在
進化：程序員 → 架構師（初級）

🧠 Week 6 五大 pattern
Pattern	解決咩	你檔案
Class + Composition	表達概念、組合對象	Todo, TodoList
Type Hint	Catch bug + IDE autocomplete	Property + method params
Dependency Injection	唔綁死 dependency	new Repo($pdo)
Repository Pattern	UI 唔理 DB	TodoRepository
MVC	關注點分離	Controller + View
🎓 你 Week 6 內化嘅 senior 直覺
主動諗 design 問題（toggle 用唔用 findById？）
主動諗 defensive（delete 唔 find 先 confirm？）
主動諗一致性（isDone() vs $done）
主動諗職責（Repository 應唔應該做業務邏輯？）
主動諗學習節奏（每月 Integration Week）
主動識別「唔明」感覺（唔逃避）
呢啲直覺，好多寫咗 2-3 年 code 嘅人都未有。

💪 Week 6 產出
5 個真實可跑嘅版本：

Day 1：Flash class OOP 版
Day 2：Todo + TodoList 演化
Day 3：User + UserList 練習
Day 5：Repository 完整版
Day 6：MVC 完整版
每個版本一步步 evolve，layer 越加越清晰。

📝 Week 6 未完成 / Week 7 會學
Interface / Abstract class（RepositoryInterface）
Autoloading（PSR-4，spl_autoload_register）
Namespace（App\Repository\TodoRepository）
簡單 Router（唔使 todo_MVC.php、user.php 個個 file）
Middleware 概念
簡單 Container / Service Locator
Week 7 學完 + Week 8 Integration → 準備接軌 Laravel。

🎯 Week 6 完成 = 你依家嘅市場位置
你依家識嘅嘢：

✅ MySQL 基本
✅ PHP OOP 完整
✅ Session / Auth
✅ MVC 架構
✅ Repository Pattern
✅ DI 概念
進度指標：

你依家嘅架構水平已達 junior PHP dev 中位數。

好多 junior 工作 1 年都未識用 Repository。你 2 個月就掌握。

