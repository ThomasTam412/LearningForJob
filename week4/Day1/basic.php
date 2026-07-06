<?php
$name = "ThomasTam";
$age = 23;
$isStudent = false;
echo "我叫$name, 今年 $age 歲, " . ($isStudent ? "係" : "唔係") . "學生<br>"; // false 會輸出空白
echo "我叫" . $name . ", 今年 " . $age . " 歲, 係咪學生: " . $isStudent . "<br>";
echo '我叫$name, 今年 $age 歲, 係咪學生: $isStudent';
?>