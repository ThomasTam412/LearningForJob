🏆 Day3 練習日 完整結束
你今日做到嘅嘢
8 個 Task 全部完成
✅ Task 1-3：基本統計（自訂 helper function）
✅ Task 4：filter + map + implode pipeline
✅ Task 5：分組統計（filter 拆班）
✅ Task 6：及格率計算
✅ Task 7：usort + spaceship + arrow function（senior 級寫法）
✅ Task 8：函數封裝 + use 傳外部變數
遇到嘅 Bug（全部自己 fix）
✅ array_filter 唔重排 index → array_values 修
✅ 忘記接返 array_values 嘅 return value → 學識 pure function 概念
✅ number_format 執行順序 → 邏輯 debug
💪 我對你今日嘅評價
呢個 quality 已經超過「初學者」範疇
一個真正嘅 junior candidate 面試會問嘅嘢，你今日全部識：

面試常問	你識唔識
陣列過濾 + 轉換	✅
Group by	✅
排序 (usort)	✅
統計計算	✅
函數封裝 + 傳參	✅
Closure / scope	✅
Debug PHP warning	✅
Pure function 概念	✅
你嘅碼比實習生好
命名清晰（getMaxGuy, getPassRate）
函數單一責任
註解分段
DRY（getClassStats 內部複用其他函數）
識用進階語法（<=>, arrow fn, use）
⚠️ 但講返一個要改嘅小習慣
你嘅函數命名有少少混雜語言：

text

getMaxGuy    ← Guy 唔正式
getPassedNum ← 應該係 getPassedCount
peopleNum    ← 應該係 count / total / studentCount
工作命名慣例
場景	慣例
數量	count, total, num (較舊)
人物	person, student, user
通過	passed, passing
最大	max, highest, top
改良版：

PHP

getMaxStudent()       // 唔係 getMaxGuy
getPassingCount()     // 唔係 getPassedNum  
$stats["count"]       // 唔係 peopleNum
$stats["topStudent"]  // 唔係 highest
呢個唔係大問題，但係 code review 一定會被人 comment。業界越大公司越 care 命名。

📊 Week4 進度
text

Day1 ✅ 環境 + PHP 基礎語法
Day2 ✅ 控制流程 + 陣列 + 迴圈 + 函數 + 陣列函數  
Day3 ✅ 綜合練習（8 個 task）
Day4 ⏳ 表單處理（GET / POST）
Day5 ⏳ Session / Cookie
Day6 ⏳ 練習日
Day7 ⏳ 休息
MySQL 我建議挪去 Week5，因為：

Day4 + Day5 表單 + Session 資訊量已經夠大
MySQL 一日教唔完（安裝、SQL 基礎、PHP 連接、CRUD）
加埋登入系統要一齊，Week4 塞唔落

今日筆記，特別記：
array_filter 唔重排 index，要 array_values
usort 直接改原 array（reference）
PHP scope 用 use ($var)
Arrow function fn() => 自動 capture
Spaceship operator <=>