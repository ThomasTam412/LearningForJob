<?php
session_start();
if (isset($_SESSION["username"]) || isset($_SESSION["role"])) {
    $username = htmlspecialchars($_SESSION["username"] ?? "");
    $role = htmlspecialchars($_SESSION["role"] ?? "");
    echo "Hello $username, you are $role";
} else {
    echo "No session data";
}
?>