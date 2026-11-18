<?php
function ImportTemplate($path) {
    $path = preg_replace("/[.\\\\\/]+/", "/", $path);
    $path = trim($path, "/");
    $file = __DIR__."/../../template/".$path.".php";

    if (is_file($file)) {
        require_once $file;
    }
}
function LoadView($path) {
    $path = preg_replace("/[.\\\\\/]+/", "/", $path);
    $path = trim($path, "/");
    $file = __DIR__."/../../view/".$path.".php";

    if (is_file($file)) {
        require_once $file;
    }
}
?>