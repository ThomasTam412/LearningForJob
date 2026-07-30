<?php
spl_autoload_register(function (string $class) {
    $file = __DIR__ . "/" . $class . ".php";
    if (file_exists($file)) {
        require $file;
    }
});