<?php
require_once "db.php";
$_SESSION = [];
session_regenerate_id(true);

set_flash("success", "You have been logged out");
header("Location: home.php");
exit;
?>