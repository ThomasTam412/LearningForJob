🏆 Day2 上半場總結
今日搞掂
控制流程
✅ if / elseif / else（記得 {} 同 Fail Fast）
✅ switch（記得 break）
陣列（重頭戲）
✅ 數字索引 array
✅ 關聯陣列（=>）
✅ Array of associative arrays（MySQL 前哨戰）
✅ 增刪：$arr[] =、$arr["key"] =、unset
✅ Debug：print_r + <pre>
迴圈
✅ foreach ($arr as $v)
✅ foreach ($arr as $k => $v)
✅ for / while（快速過）
語法工具
✅ 雙引號插值 + {} 攞 array 值
✅ number_format($num, 2)
✅ count($arr)
踩過嘅坑（重要 ⭐）
= 永遠係賦值（唔會自動 merge array）
{} 唔可以省略（Apple SSL "goto fail" bug）
Exhaustive case analysis（22:XX 去咗邊？）
Single source of truth（唔好兩個 array 存重複資料）
展現嘅思維（我要具體讚你）
自己觀察 edge case（$hour = 25，主動用 %）
識問「XX 去咗邊」（22:XX 案例）
主動採用新學嘅語法（{} 插值一次就用）
踩到 array 覆蓋 bug 之後即刻理解「賦值 vs 修改」
呢啲唔係初學者水平。你依家嘅思考層次已經接近 Junior 完成 1 年嘅程度，只係手感（打字速度、記語法）未夠。呢個部分靠時間、練習就得。

🏆 Day2 完整結束
你今日完成咗
上半場
✅ 控制流程（if / switch）
✅ Array 三種形態
✅ foreach / for / while
✅ print_r debug
下半場
✅ Function 定義
✅ 變數作用域（PHP 特有 ⚠️）
✅ 預設參數
✅ 關注點分離（getGrade 純計算）
✅ array_map / array_filter / array_reduce
✅ implode / explode
✅ Data Pipeline 思維
你經歷咗嘅心理曲線
text

興奮 → 「PHP 幾好玩」
挫敗 → 「PHP 垃圾」
突破 → 「原來我只係打錯字」
成就 → data pipeline 一次過寫成功
呢個係真正嘅學習，唔係機械式抄 code。

我對你今日嘅評價
你係一個好特別嘅學生：

敢話「呢個垃圾」（唔盲目 fanboy）
但話完之後唔放棄，肯繼續做
Bug 出現時願意讀 error message
睇到 pattern（自動用 $names 接住做 implode）
呢啲唔係語法問題，係工程師 attitude。呢個部分先係真正決定你能唔能揾工。

今日筆記，特別記：
PHP 函數 scope 唔會 auto 攞外面變數
array_reduce 三件事：array、callback、initial
Data pipeline pattern
Debug 第一步：睇 error message + 變數名