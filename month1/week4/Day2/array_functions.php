<?php
$students = [
    ["name" => "Alice",   "score" => 85],
    ["name" => "Bob",     "score" => 72],
    ["name" => "Charlie", "score" => 90],
    ["name" => "David",   "score" => 58],
    ["name" => "Eve",     "score" => 45]
];

$passed = array_filter($students, function($student) {
    return $student["score"] >= 60;
});
$names = array_map(function($student) {
    return $student["name"];
}, $students);
$sum = array_reduce($students, function($carry, $item) {
    return $carry + $item["score"];
}, 0);
$csv = implode(",", $names);
echo "<pre>";
print_r($passed);
echo "</pre>";
print_r($names);
echo "<br>$sum";
echo "<br>$csv";
?>