<?php
require_once __DIR__."/install.inc.php";
require_once __DIR__."/schema.inc.php";

$eSetupApp = $_SESSION["setup_application"] ?? 0;
$eSetupToken = $_SESSION["setup_token"] ?? "";
$vCsrfToken = $_POST["csrf_token"] ?? "";

if (SetupIsLocked() || !SetupHasSchema() || $eSetupApp !== 1 || $eSetupToken === "") {
    header("Location: /setup", true, 303);
    exit;
} elseif (!is_string($vCsrfToken) || !hash_equals($eSetupToken, $vCsrfToken)) {
    header("Location: /setup?err=invalid-setup-token", true, 303);
    exit;
}
$eUserOptions = $_SESSION["setup_mysql"]["user_options"];
$eUserOptions["username"] = $_POST["username"] ?? "";
$eUserOptions["password"] = $_POST["password"] ?? "";

if (!is_string($eUserOptions["username"]) || !is_string($eUserOptions["password"])) {
    header("Location: /setup?err=invalid-username", true, 303);
    exit;
}
$errorCode = SetupMysqlCheckOptions($eUserOptions);

if ($errorCode !== "") {
    header("Location: /setup?err=".$errorCode, true, 303);
    exit;
}
try {
    SetupMysqlConnect(array_merge($eUserOptions, ["database" => ""]));
} catch (PDOException $exception) {
    header("Location: /setup?err=error-connection", true, 303);
    exit;
}
$envFile = SetupEnvFile();

if (!is_file($envFile) && !@touch($envFile)) {
    header("Location: /setup?err=env-write-failed", true, 303);
    exit;
}
$errorCode = SetupInstallSchema($_SESSION["setup_mysql"]["main_options"]);

if ($errorCode !== "") {
    header("Location: /setup?err=".$errorCode, true, 303);
    exit;
}

if (!UpdateEnv($envFile, [
    "MYSQL_HOST" => '"'.$eUserOptions["host"].'"',
    "MYSQL_PORT" => '"'.$eUserOptions["port"].'"',
    "MYSQL_DATABASE" => '"'.$eUserOptions["database"].'"',
    "MYSQL_USERNAME" => '"'.$eUserOptions["username"].'"',
    "MYSQL_PASSWORD" => '"'.$eUserOptions["password"].'"',
])) {
    header("Location: /setup?err=env-write-failed", true, 303);
    exit;
}

$_SESSION["setup_mysql"]["user_options"] = $eUserOptions;
$_SESSION["setup_application"] = 2;
require __DIR__."/index.php";
?>