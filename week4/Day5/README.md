📘 Week 4 Day 5 學習筆記
主題：Session / Cookie
🎯 核心觀念
為咩需要 Session / Cookie？
HTTP 係 stateless（無狀態）協定
每次 request 對 server 嚟講都係「第一次見你」
要「記住用戶」就要靠額外機制
Cookie vs Session 本質差異
項目	Cookie	Session
資料位置	瀏覽器	Server
瀏覽器保存	完整資料	只有 session id
安全性	低（用戶可改）	高
用途	偏好、主題、語言	登入狀態、購物車
底層	HTTP header	都係用 cookie（PHPSESSID）
重要領悟：Session 底層都係用 cookie，只係內容變咗「識別證」而唔係「真資料」

🔧 Cookie API
設定
PHP

setcookie("name", "value", time() + 3600);  // 1 小時後過期
讀取
PHP

$_COOKIE["name"]
刪除
PHP

setcookie("name", "", time() - 3600);  // 過期時間設過去
檢查
PHP

isset($_COOKIE["name"])
🔧 Session API
開啟（每個檔案最頂）
PHP

session_start();
設定
PHP

$_SESSION["key"] = "value";
讀取
PHP

$_SESSION["key"]
部分刪除
PHP

unset($_SESSION["key"]);
完全銷毀（登出）
PHP

$_SESSION = [];
session_destroy();
⚠️ 陷阱與鐵律
setcookie() / session_start() 前唔可以有任何 output（包括 echo、HTML、空白）

因為要送 HTTP header
Cookie 綁 Domain + Path

localhost ≠ 127.0.0.1（cookie 唔共享）
Port 唔影響 cookie
刪 cookie 時要用返同一個 path
Session Cookie（PHPSESSID）預設關瀏覽器就冇

Expires 顯示 Session 就係呢個意思
Server 層 timeout 預設 1440 秒（24 分鐘）
想長期記住登入 → 要做 Remember me 機制
Session 資料未必安全

Source 可能來自用戶輸入
Output 一律要 htmlspecialchars()
🆕 新學到嘅 PHP 語法
Null Coalescing Operator ??
PHP

$name = $_SESSION["username"] ?? "Guest";
// 如果存在且非 null → 用佢
// 否則 → 用 "Guest"
isset() 多參數
PHP

isset($_SESSION["username"], $_SESSION["role"])
// 等同 isset(a) && isset(b)
💡 工程思維
Idempotent 操作：登出唔理狀態直接執行（做一次同做十次結果一樣）
Escape 嘅時機：string 要 escape，int / bool 唔使
主要 key 判斷 pattern：用一個 key 判斷登入狀態，其他資料當附加
= 0 vs unset 嘅語意差異：
= 0：「呢個值歸零，但變數依然應該存在」
unset：「呢個變數應該徹底消失」
🏗️ 實作產出
✅ set_cookie.php / get_cookie.php / delete_cookie.php
✅ set_session.php / get_session.php / destroy_session.php
✅ visit_counter.php / reset_counter.php（迷你組合實戰）
🧠 深入理解嘅發現
PHPSESSID cookie 存喺瀏覽器（DevTools 可以睇）
真正 session 資料存喺 server file（例：C:\xampp\tmp\sess_xxx）
關瀏覽器 ≠ session 過期（要分瀏覽器層 vs server 層）