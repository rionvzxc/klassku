<?php
function UseDbms($path, $options = []) {
    $path = preg_replace("/[.\\\\\/]+/", "/", $path);
    $path = trim($path, "/");
    $file = __DIR__."/../database/".$path."/connection.php";

    if (is_file($file)) {
        require $file;
    }
}
?>