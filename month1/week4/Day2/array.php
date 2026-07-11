<?php
$colors = ["red", "green", "blue"];

$student = [
    "name" => "Thomas",
    "score" => 85,
    "passed" => true
];

$scores = [90, 85, 78];

$student["email"] = "thomas@test.com";
$scores[] = 95;

echo $colors[0] . "<br>";
echo count($colors) . "<br>";

echo $student["name"]. " 成績 " . $student["score"] . " 是否合格: " . ($student["passed"] ? "是" : "否") . "<br>";
echo "<pre>";
print_r($student);
echo "</pre>";
print_r($scores);

?>