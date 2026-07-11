<?php
if (isset($_COOKIE["username"])) {
    $username = $_COOKIE["username"];
    $safeUsername = htmlspecialchars($username);
    echo "Hello $safeUsername";
} else {
    echo "Cookie has not been set";
}

?>