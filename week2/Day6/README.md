📘 Week2 Day6 學習總結
🎯 今日主題
綜合練習日：整合 Week2 全部所學

✅ 完成項目
題號	題目	完成度	評價
Q1	計數器	✅ 100%	UI 精緻、邏輯乾淨
Q2	猜數字遊戲	✅ 100%（含 bonus）	Production-ready 等級
Q3	Todo List	⏸ 改天再戰	—
📚 整合運用的知識（Week1 + Week2）
Week1（CSS）
Flexbox / Grid 置中
box-shadow、border-radius、transition
:hover、:focus 互動效果
屬性選擇器 input[type="number"]
DRY 共用 class（.btn）
Week2（JS）
變數（let / const）、Template Literals
條件判斷 + Early return
函式抽出（單一職責）
閉包（變數放外面累積）
DOM 選取 + 事件監聽
隨機數、Number() 轉型、input.value
disabled 屬性控制
⚠️ 今天踩過的坑（超有價值）
坑 1：Cannot read properties of null
原因： querySelector 選錯，回傳 null
教訓： 看到這錯誤先 console.log 那個變數
坑 2：Assignment to constant variable
原因： 想對 const DOM 元素重新賦值
教訓： 改 DOM 元素屬性（.textContent），不是改變數本身
坑 3：updateDisplay() 偷偷 frequency++
原因： 函式做了名稱沒寫的事（副作用）
教訓： 單一職責 — 名字叫 update 就只更新，不改資料
坑 4：驗證失敗時次數也 +1
原因： frequency++ 放在驗證之前
教訓： 計數應該只在「有效操作」後才執行
坑 5：為了複用 updateDisplay() 扭曲資料
原因： 設 minNum = maxNum = num 讓 hint 顯示答案
教訓： 資料應該誠實反映狀態，不要為了複用而硬湊
🧠 你今天學到的工程思維（最重要 ⭐）
單一職責原則（SRP） —— 一個函式做一件事
Early return —— 驗證失敗立刻跳出，避免巢狀
DRY —— 重複的 CSS / 邏輯抽出共用
不要保留死 code —— 註解掉的舊 code 不要 commit
資料和顯示要同步 —— 改了狀態就要更新畫面
錯誤訊息會說人話 —— Cannot read properties of null = 那個東西不存在
📝 GitHub Commit Message 建議
text

week2-day6: 綜合練習日 - 計數器 + 猜數字遊戲

Q1 計數器：
- 三按鈕（+1 / -1 / Reset）
- 數字顏色根據正負/零變化
- updateDisplay 抽出單一職責函式

Q2 猜數字遊戲：
- 1~100 隨機數
- 範圍提示（minNum ~ maxNum）
- 完整輸入驗證（空輸入、非數字、超範圍）
- 猜中後禁用按鈕、清空輸入
- 重新開始完整重置狀態
- UI：藍色科技風配色、focus 發光、hover 過渡
🚀 Week2 即將完結
明天 Day7 你有兩個選擇：

選項 A：把 Q3 Todo List 做掉（推薦 ⭐）
學會「動態新增 / 刪除元素」這個 Web 開發超核心技能
完成後 Week2 就是完美收官
選項 B：完全休息
大腦也需要消化
Day7 本來就是彈性日
明天告訴我你的選擇就好，
不用解釋，誠實面對自己的狀態 💪

❤ 老實說
從 Day1 你打 console.log("JS is working")，
到今天 Day6 你寫出一個有狀態管理、有驗證邏輯、有完整 UI 的猜數字遊戲，
6 天而已。

你的進步速度，老實說讓我意外。

先別自我懷疑，去 commit 然後好好睡 ✅
明天見！