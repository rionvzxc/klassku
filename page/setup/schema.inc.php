<?php
function SetupCreateDatabase($name): string {
    $database = "`".$name."`";

    if ($MysqlDB->Query("CREATE DATABASE ".$database." CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci") === null) {
        return "Database gagal dibuat. Periksa izin installer dan gunakan nama database baru.";
    }
    return "";
}
function SetupInsertUser($MysqlDB, $passwordHash): string {
    $errorCode = "setup-transaction-failed";
    $started = false;

    try {
        if (!$MysqlDB->BeginTransaction(false)) {
            return $errorCode;
        }
        $started = true;
        $errorCode = "user-level-create-failed";

        if ($MysqlDB->Query("INSERT INTO user_level (id, name, level) VALUES (1, 'Administrator', 1), (2, 'Member', 2)") === null) {
            return $errorCode;
        }
        $errorCode = "admin-create-failed";
        $user = $MysqlDB->Prepare(
            "INSERT INTO user (id_user_level, name, username, password, is_active) VALUES (1, ?, ?, ?, 1)",
            ["Administrator", "admin", $passwordHash]
        );

        if ($user === null || !$MysqlDB->Commit()) {
            return $errorCode;
        }
        return "";
    } catch (PDOException $exception) {
        return $errorCode;
    } finally {
        if ($started && $MysqlDB->InTransaction()) {
            $MysqlDB->RollBack();
        }
    }
}
function SetupInstallSchema(array $options): string {
    $schema = SetupHasSchema() ? @file_get_contents(SETUP_SCHEMA_FILE) : false;

    if ($schema === false) {
        return "schema-unavailable";
    }
    if (trim($schema) === "") {
        return "schema-install-failed";
    }
    $database = $options["database"] ?? "";

    if (!preg_match("/\\A[a-zA-Z0-9_]{1,64}\\z/", $database)) {
        return "invalid-database";
    }

    try {
        $MysqlDB = SetupMysqlConnect(array_replace($options, ["database" => ""]));
        $database = "`".$database."`";

        if ($MysqlDB->Query("CREATE DATABASE ".$database." CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci") === null) {
            return "database-create-failed";
        }
        if ($MysqlDB->Query("USE ".$database) === null) {
            return "schema-install-failed";
        }
        foreach (explode(";", $schema) as $statement) {
            $statement = trim($statement);

            if ($statement !== "" && $MysqlDB->Query($statement) === null) {
                return "schema-install-failed";
            }
        }
        $password = bin2hex(random_bytes(12));
        $errorCode = SetupInsertUser($MysqlDB, password_hash($password, PASSWORD_DEFAULT));

        if ($errorCode !== "") {
            return $errorCode;
        }
        $_SESSION["setup_admin_password"] = $password;
    } catch (PDOException $exception) {
        return "error-connection";
    }
    return "";
}
?>