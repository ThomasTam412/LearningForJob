<?php
// 1. Method check
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("請經表單存取");
}
// 2. 取輸入
$username = $_POST["username"] ?? "";
$email = $_POST["email"] ?? "";

if ($username === "") {
    die("必須填寫用戶名");
}
if ($email === "") {
    die("必須填寫電郵");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("電郵格式錯誤");
}

// 3. Escape output
$safeUsername = htmlspecialchars($username);
$safeEmail = htmlspecialchars($email);

echo "註冊成功!<br>";
echo "使用者名稱: $safeUsername<br>";
echo "電郵: $safeEmail<br>";
?>