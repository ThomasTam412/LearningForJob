<?php
if (isset($_COOKIE["username"])) { // 實際操作唔作檢查直接刪!!!
    if (setcookie("username", "", time() - 1)) {
        echo "Cookie has been delete";
    } else {
        echo "Cookie has not been delete";
    }
} else {
    echo "Cookie has not been set";
}
?>