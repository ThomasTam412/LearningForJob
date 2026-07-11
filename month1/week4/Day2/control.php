<?php
$hour = 14;
if ($hour < 0 || $hour >= 24) {
    echo "無效時間";
}
elseif ($hour >= 6 && $hour < 12) {
    echo "早晨<br>";
}    
elseif ($hour >=12 && $hour < 18) {
    echo "下午<br>";
}
elseif ($hour >=18 && $hour < 22) {
    echo "晚上<br>";
}
else {
    echo "深夜<br>";
}
?>