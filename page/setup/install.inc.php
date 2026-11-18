<?php
const SETUP_SCHEMA_FILE = __DIR__."/../../schema.sql";

function SetupHasSchema(): bool {
    return is_file(SETUP_SCHEMA_FILE) && is_readable(SETUP_SCHEMA_FILE);
}
function SetupIsLocked(): bool {
    $file = __DIR__."/../../storage/setup.lock";
    return is_file($file) && trim((string)@file_get_contents($file)) === "installed";
}
function SetupEnvFile(): string {
    $profile = $_ENV["APP_ENV"] ?? "";
    $file = __DIR__."/../../.env";

    if (in_array($profile, ["development", "production"], true)) {
        $file .= ".".$profile;
    }
    return $file;
}
function SetupMysqlConnect(array $options): MysqlDatabase {
    global $MysqlDB;
    UseDbms("mysql", $options);
    return $MysqlDB;
}
function SetupMysqlCheckOptions(array $options = []): string {
    $host = trim($options["host"] ?? "");
    $port = filter_var($options["port"] ?? "", FILTER_VALIDATE_INT);
    $database = $options["database"] ?? "";
    $username = $options["username"] ?? "";
    $password = $options["password"] ?? "";

    if (strtolower($host) !== "localhost" &&
        !filter_var($host, FILTER_VALIDATE_IP) &&
        !filter_var($host, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME)
    ) {
        return "invalid-host";
    } elseif ($port === false || $port < 1 || $port > 65535) {
        return "invalid-port";
    } elseif (!preg_match("/^[a-zA-Z0-9_]+$/", $database)) {
        return "invalid-database";
    } elseif (!preg_match("/^[a-zA-Z0-9_]+$/", $username)) {
        return "invalid-username";
    }
    return "";
}
?>