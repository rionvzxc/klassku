<?php
function ImportUtil($path) {
    $path = preg_replace("/[.\\\\\/]+/", "/", $path);
    $path = trim($path, "/");
    $file = __DIR__."/../../util/".$path.".php";

    if (is_file($file)) {
        require_once $file;
    }
}
?>