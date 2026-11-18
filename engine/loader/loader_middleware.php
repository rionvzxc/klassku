<?php
function UseMiddleware($path) {
    global $MysqlDB;

    $path = preg_replace("/[.\\\\\/]+/", "/", $path);
    $path = trim($path, "/");
    $file = __DIR__."/../../middleware/".$path.".php";

    if (is_file($file)) {
        require_once $file;
    }
}
?>