<?php
session_start();
if (!isset($_SESSION["count"])) {
   $_SESSION["count"] = 0; 
}
$count = $_SESSION["count"] + 1;
$_SESSION["count"] = $count;
echo "You have visited this page ". htmlspecialchars($count) . " times";
?>