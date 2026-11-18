<?php
$eIdUser = $_SESSION["id_user"] ?? "";
$username = $_POST["username"] ?? "";
$password = $_POST["password"] ?? "";

if ($eIdUser !== "") {
    header("Location: /");
    exit;
}
$errMsg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $stmt = $MysqlDB->Prepare(
        "SELECT id, id_user_level, username, password FROM user WHERE username = ? AND is_active = 1 LIMIT 1",
        [$username]);
    $resUser = false;

    if ($stmt) {
        $resUser = $stmt->fetch();
    }
    if ($resUser && password_verify($password, $resUser["password"])) {
        session_regenerate_id(true);
        $_SESSION["id_user"] = $resUser["id"];
        $_SESSION["username"] = $resUser["username"];
        $_SESSION["id_user_level"] = $resUser["id_user_level"];

        header("Location: /");
        exit;
    }
    $errMsg = "Username atau password salah.";
}
LoadView("page/login");
ViewLogin([
    "username" => $username,
    "error_message" => $errMsg
], null);
?>