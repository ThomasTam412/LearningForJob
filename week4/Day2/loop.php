<?php
$students = [
    ["name" => "Alice",   "score" => 85],
    ["name" => "Bob",     "score" => 72],
    ["name" => "Charlie", "score" => 90],
    ["name" => "David",   "score" => 58]
];
$counter = 0;
$sum = 0;
foreach ($students as $student) {
    $counter++;
    $sum += $student["score"];
    echo "$counter. {$student['name']}: {$student['score']} 分 - " . ($student["score"] >= 60 ? "合格" : "不合格") . "<br>";
}
$avg = $sum / count($students);
echo "平均分: " . number_format($avg, 2) . "分";
?>