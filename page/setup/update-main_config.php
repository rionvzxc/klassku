<?php
require_once __DIR__."/install.inc.php";

$eSetupApp = $_SESSION["setup_application"] ?? 0;
$eSetupToken = $_SESSION["setup_token"] ?? "";
$vCsrfToken = $_POST["csrf_token"] ?? "";

if (SetupIsLocked() || !SetupHasSchema() || $eSetupApp !== 0 || $eSetupToken === "") {
    header("Location: /setup", true, 303);
    exit;
} elseif (!is_string($vCsrfToken) || !hash_equals($eSetupToken, $vCsrfToken)) {
    header("Location: /setup?err=invalid-setup-token", true, 303);
    exit;
}
$vMainOptions = [
    "host" => $_POST["host"] ?? "",
    "port" => $_POST["port"] ?? "",
    "username" => $_POST["username"] ?? "",
    "password" => $_POST["password"] ?? "",
    "database" => $_POST["database"] ?? "",
];
$_SESSION["setup_mysql"]["main_options"] = $vMainOptions;
$errorCode = SetupMysqlCheckOptions($vMainOptions);

if ($errorCode !== "") {
    header("Location: /setup?err=".$errorCode, true, 303);
    exit;
}
try {
    $MysqlDB = SetupMysqlConnect(array_merge($vMainOptions, ["database" => ""]));
} catch (PDOException $exception) {
    header("Location: /setup?err=error-connection", true, 303);
    exit;
}
$stmt = $MysqlDB->Query("SHOW DATABASES LIKE ".$MysqlDB->Quote($vMainOptions["database"]));

if ($stmt === null) {
    header("Location: /setup?err=database-check-failed", true, 303);
    exit;
}
if ($stmt->fetch()) {
    header("Location: /setup?err=database-already-exists", true, 303);
    exit;
}
$_SESSION["setup_mysql"]["user_options"] = $vMainOptions;
$_SESSION["setup_mysql_version"] = $MysqlDB->PDO()->getAttribute(PDO::ATTR_SERVER_VERSION);
$_SESSION["setup_application"] = 1;
header("Location: /setup", true, 303);
exit;
?>