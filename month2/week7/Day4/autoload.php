<?php
spl_autoload_register(function (string $class) {
    $prefix = "App\\";
    $baseDir = __DIR__ . "/app/";
    
    if (str_starts_with($class, $prefix) === false) {
        return;
    }

    $relative = substr($class, strlen($prefix));
    $file = $baseDir . str_replace("\\", "/", $relative) . ".php";

    if (file_exists($file)) {
        require $file;
    }
});