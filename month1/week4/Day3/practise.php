<?php
$students = [
    ["name" => "Alice",   "score" => 85, "class" => "A"],
    ["name" => "Bob",     "score" => 72, "class" => "B"],
    ["name" => "Charlie", "score" => 90, "class" => "A"],
    ["name" => "David",   "score" => 58, "class" => "B"],
    ["name" => "Eve",     "score" => 45, "class" => "A"],
    ["name" => "Frank",   "score" => 78, "class" => "B"],
    ["name" => "Grace",   "score" => 92, "class" => "A"],
    ["name" => "Henry",   "score" => 65, "class" => "B"]
];

// Task 1: 印出所有學生資訊，格式: 1. Alice (Class A): 85 分 - B 級
function getGrade($student) {
    if ($student["score"] >= 90) {
        return "A";
    } elseif ($student["score"] >= 80) {
        return "B";
    } elseif ($student["score"] >= 60) {
        return "C";
    } else {
        return "F";
    }
}
function formatStudent($student) {
    $grade = getGrade($student);
    return "{$student['name']} (Class {$student['class']}): {$student['score']} 分 - $grade 級";
}
foreach ($students as $index => $student) {
    echo ($index + 1) . ". " . formatStudent($student) . "<br>";
}
echo "<br>";
// Task 2: 全部人的平均分
function getAvg($students) {
    $total  = array_reduce($students, function($carry, $item) {
        return $carry + $item["score"];
    }, 0);
    $avg = $total / count($students);
    $num = number_format($avg, 2);
    return $num;
}
echo "平均分(不分班): " . getAvg($students) . "分<br>";
echo "<br>";

// Task 3: 全部人的最高最低分
function getMaxGuy($students) {
    $max = $students[0]["score"];
    $maxPos = 0;
    foreach($students as $index =>$student) {
        if ($max < $student["score"]) {
            $max = $student["score"];
            $maxPos = $index;
        }
    }
    return $students[$maxPos];
}
function getMinGuy($students) {
    $min = $students[0]["score"];
    $minPos = 0;
    foreach($students as $index =>$student) {
        if ($min > $student["score"]) {
            $min = $student["score"];
            $minPos = $index;
        }
    }
    return $students[$minPos];
}
$maxGuy = getMaxGuy($students);
$minGuy = getMinGuy($students);
echo "最高分(不分班): {$maxGuy['name']} ({$maxGuy['score']} 分) <br>";
echo "最低分(不分班): {$minGuy['name']} ({$minGuy['score']} 分) <br>";
echo "<br>";

// Task 4: 印出合格學生的名單
$passed = array_filter($students, function($student) {
    return $student["score"] >= 60;
});
$passedName = array_map(function($student) {
    return $student["name"];
}, $passed);
$csv = implode(",", $passedName);
echo "合格學生: $csv <br>";
echo "<br>";

// Task 5: 分班的平均分;
$classA = array_filter($students, function($student) {
    return $student["class"] === "A";
});
$classB = array_filter($students, function($student) {
    return $student["class"] === "B";
});
echo "Class A 平均: " . getAvg($classA) . "分<br>";
echo "Class B 平均: " . getAvg($classB) . "分<br>";
echo "<br>";

// Task 6: 班別合格率
function getPassedNum($students) {
    $passed = array_filter($students , function($student) {
        return $student["score"] >= 60;
    });
    $passedNum = count($passed);
    return $passedNum;
}
function getPassRate($students) {
    $rate = getPassedNum($students) / count($students);
    $percent = $rate * 100;
    return number_format($percent, 2);
}
echo "Class A 合格率: " . getPassRate($classA) . "%(" . count($classA) . " 人中" . getPassedNum($classA) . " 人合格)<br>";
echo "Class B 合格率: " . getPassRate($classB) . "%(" . count($classB) . " 人中" . getPassedNum($classB) . " 人合格)<br>";
echo "<br>";

// Task 7: 排行
usort($students, fn($a, $b) => $b["score"] <=> $a["score"]); // $b <=> $a 係由大到小
foreach($students as $index => $student) {
    echo ($index + 1) . ". {$student['name']} ({$student['score']} 分)<br>";
}

// Task 8:用一個函數封裝「班別統計」邏輯
function getClassStats($students, $className) {
    $classStats = [];
    $class = array_values(array_filter($students, function($student) use ($className){
        return $student["class"] === $className;
    }));
    $classStats["peopleNum"] = count($class);
    $classStats["avg"] = getAvg($class);
    $classStats["passRate"] = getPassRate($class);
    $classStats["highest"] = getMaxGuy($class)["name"];
    return $classStats;
}
echo "<pre>";
print_r(getClassStats($students, "A"));
print_r(getClassStats($students, "B"));
echo "</pre>";
?>