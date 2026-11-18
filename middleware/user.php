<?php
$eIdUser = $_SESSION["id_user"] ?? "";
$isValidUser = false;

if ($eIdUser !== "") {
    $resUser = $MysqlDB->Prepare(
        "SELECT id, username, id_user_level FROM user WHERE id = ? AND is_active = 1 LIMIT 1",
        [$eIdUser]
    )->fetch();
    if ($resUser) {
        $isValidUser = true;
    }
}
if (!$isValidUser) {
    $_SESSION = [];

    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            "",
            time() - 3600,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }
    session_destroy();
    header("Location: /login/");
    exit;
} else {
    $_SESSION["id_user"] = $resUser["id"];
    $_SESSION["username"] = $resUser["username"];
    $_SESSION["id_user_level"] = $resUser["id_user_level"];
}
?>
