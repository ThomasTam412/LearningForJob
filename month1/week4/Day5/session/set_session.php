<?php
session_start();
$_SESSION["username"] = "Thomas";
$_SESSION["role"] = "admin";
echo "Session has been set.";
?>