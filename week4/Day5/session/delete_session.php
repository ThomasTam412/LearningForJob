<?php
session_start();
$_SESSION = [];
session_destroy();
echo "Session data has been delete";
?>