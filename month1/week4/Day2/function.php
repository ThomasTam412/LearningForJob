<?php
$students = [
    ["name" => "Alice", "score" => 85],
    ["name" => "Bob", "score" => 72],
    ["name" => "Charlie", "score" => 90],
    ["name" => "David", "score" => 58]
];

function getGrade($score) {
    if ($score >= 90) {
        return "A";
    }
    elseif ($score >= 80) {
        return "B";
    }
    elseif ($score >= 70) {
        return "C";
    }
    elseif ($score >= 60) {
        return "D";
    }
    else {
        return "F";
    }
}
function printStudent($student) {
    $Grade = getGrade($student["score"]);
    echo "{$student['name']}: {$student['score']} 分 - $Grade 級<br>";
}
foreach ($students as $student) {
    printStudent($student);
}
?>