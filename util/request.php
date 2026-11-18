<?php
function SplitRequestPath() {
    $path = strtolower(GetPagePath(GetRequestPath()));
    $path = preg_replace("/\\.php$/D", "", $path);
    $path = preg_replace("/\\/index$/D", "", $path);

    if ($path === "" || $path === "index") {
        return [];
    }
    return explode("/", "page/".$path);
}
function GetRequestPath() {
    $path = str_replace("\\", "/", rawurldecode(GetRequestUrlPath()));
    return implode("/", array_diff(explode("/", $path), ["", ".", ".."]));
}
function GetPagePath($path) {
    if ($path === "page") {
        return "";
    }
    return str_starts_with($path, "page/") ? substr($path, 5) : $path;
}
function GetRequestUrlPath() {
    return explode("?", $_SERVER["REQUEST_URI"] ?? "/", 2)[0];
}
function GetUrlPath() {
    $path = parse_url($_SERVER["REQUEST_URI"] ?? "/", PHP_URL_PATH) ?: "/";
    $path = preg_replace("/\.*[\\\\\/]+/", "/", $path);
    return trim($path, "/");
}
?>
