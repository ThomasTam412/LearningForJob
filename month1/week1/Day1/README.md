# Week 1 Day 1 學習記錄

## 今日主題
- Git 基礎操作
- HTML 基本結構
- 常用 HTML 標籤
- 表格與表單練習
- GitHub 推送流程

---

## 今日學習內容

### 1. Git 基礎
今天學會了以下 Git 指令：

```bash
git init
git config --global user.name "THC"
git config --global user.email "我的GitHub郵箱"
git add .
git commit -m "提交訊息"
git push
git status
```
理解了 Git 的基本流程：

修改檔案 -> git add -> git commit -> git push

### 2. HTML 基本結構
今天手打了最基本的 HTML 網頁結構：

```html
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>網頁標題</title>
</head>
<body>
</body>
</html>
```
理解了：
<!DOCTYPE html>：宣告 HTML5
<html>：整個網頁
<head>：網頁資訊
<body>：實際顯示內容

### 3. 今天學到的 HTML 標籤
標題：`h1 ~ h6`
段落：`p`
連結：`a`
圖片：`img`
清單：`ul`、`ol`、`li`
表格：`table`、`tr`、`th`、`td `、`thead`、`tbody`
表單：`form`、`label`、`input`、`select`、`option`、`textarea`、`button`

## 今日完成的練習

### 練習 1：個人名片頁面
檔案：
- namecard.html
內容包含：
- 姓名
- 自我介紹
- 興趣清單
- Google 連結
- Pikachu 圖片
學到：
- img 需要正確的圖片路徑
- alt 屬性很重要
- a 可以連到外部網站

### 練習 2：課程表
檔案：
- timetable.html
內容包含：
- 星期一到星期五課程
- 4 節課
- table、thead、tbody、tr、th、td
學到：
- 表格標題要用 th
- 資料內容用 td
- 表格可以用 thead 和 tbody 讓結構更清晰
- border="1" 可以先讓表格顯示邊框

### 練習 3：聯絡表單
檔案：
- contact.html
內容包含：
- 姓名輸入框
- 郵箱輸入框
- 性別單選按鈕
- 詢問類型下拉選單
- 留言欄位
- 提交按鈕
- 重設按鈕
學到：
- 表單要用 form
- label 可以對應 input
- radio 的 name 要一樣，才能單選
- textarea 適合輸入較長文字

## 今日遇到的問題與解決方法

### 問題 1：Git 指令拼錯
錯誤：
- git confing
修正：
- git config
學到：
- 命令列大小寫和拼寫都要仔細
- 看錯誤訊息很重要

### 問題 2：git add . 報錯
錯誤：
- error: 'week1/' does not have a commit checked out
- fatal: adding files failed
原因：
- 子資料夾裡可能又有一個 .git
- 造成 Git 倉庫嵌套問題
學到：
- 一個專案通常只在最外層 git init 一次

### 問題 3：git push 登入失敗
錯誤：
- Logon failed
解決：
- 使用 GitHub Token
- 更新遠端網址
- 成功推送到 GitHub
學到：
- GitHub 現在不能直接用密碼 push
- Token 要妥善保管，不可以隨便外洩

### 問題 4：直接 git push 但沒有新內容
錯誤：
- Everything up-to-date
原因： 
- 還沒有先 git add 和 git commit
學到：
- push 之前一定要先 add 和 commit

### 問題 5：聯絡表單漏了留言欄位
解決：
- 補上 textarea
學到：
- 寫完功能後要回頭對照需求檢查

## 今日成果

今天成功完成了：
- 建立本地 Git 倉庫
- 設定 Git 使用者資訊
- 建立 GitHub 倉庫
- 成功把本地代碼推到 GitHub
- 手寫 3 個 HTML 練習頁面
- 學會 HTML 表格與表單基本用法
GitHub 倉庫：
- https://github.com/ThomasTam412/LearningForJob

## 今日反思
### 做得好的地方
- 願意手打代碼
- 願意自己發現錯誤
- 遇到問題沒有放棄
- 有把學習過程真的跑通

### 需要改進的地方
- Git 指令還不熟
- 易漏掉需求細節
- 對 HTML 標籤還需要更多練習
- 推送流程還需要再熟悉

## 明日計劃
明天準備學習：
- CSS 基礎
- 顏色、字體、邊距
- 讓今天做的 HTML 頁面變得更好看

預計練習：
- 美化個人名片頁
- 美化課程表
- 美化聯絡表單