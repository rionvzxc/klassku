<?php
require_once __DIR__."/loader/loader_env.php";
require_once __DIR__."/loader/loader_util.php";
require_once __DIR__."/loader/loader_view.php";
require_once __DIR__."/loader/loader_database.php";
require_once __DIR__."/loader/loader_middleware.php";
?>

<?php
session_start();
LoadEnv();
LoadView("error");
ImportUtil("request");
ImportUtil("string");

global $UrlPath;
$UrlPath = GetUrlPath();
$requiredEnv = [
    "MYSQL_HOST",
    "MYSQL_PORT",
    "MYSQL_DATABASE",
    "MYSQL_USERNAME",
    "MYSQL_PASSWORD"
];
$allowEmpty = [
    "MYSQL_PASSWORD"
];
$envReady = CheckEnv($requiredEnv, $allowEmpty);
$isSetup = str_starts_with($UrlPath, "setup");

if ($isSetup) {
    if ($envReady) {
        header("Location: /");
        exit;
    }
} elseif (!$envReady) {
    header("Location: /setup/");
    exit;
} else {
    UseDbms("mysql", []);
    require_once __DIR__."/../config/config.php";
    require_once __DIR__."/../middleware/middleware.php";
}
?>