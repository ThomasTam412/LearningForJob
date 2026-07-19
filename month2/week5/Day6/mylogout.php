<?php
require_once "db.php";
$_SESSION = [];
session_regenerate_id(true);
set_flash("success", "成功登出");
header("Location: myhome.php");
?>