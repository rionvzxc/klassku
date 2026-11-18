<?php
$eIdUser = $_SESSION["id_user"];
$name = $_POST["name"] ?? "";
$username = $_POST["username"] ?? "";

if ($name === "" || $username === "") {
    header("Location: ./?err=empty-field");
    exit;
}
$stmt = $MysqlDB->Prepare(
    "SELECT id FROM user WHERE username = ? AND id != ? LIMIT 1",
    [$username, $eIdUser]
);
$resCheck = $stmt ? $stmt->fetch() : false;

if ($resCheck) {
    header("Location: ./?err=username-exists");
    exit;
}
$stmt = $MysqlDB->Prepare(
    "UPDATE user SET name = ?, username = ?
    WHERE id = ? AND is_active = 1",
    [$name, $username, $eIdUser]
);
if (!$stmt || $stmt->rowCount() === 0) {
    $stmt = $MysqlDB->Prepare(
        "SELECT id FROM user WHERE id = ? AND is_active = 1 LIMIT 1",
        [$eIdUser]
    );
    if (!$stmt || !$stmt->fetch()) {
        header("Location: ./?err=account-inactive");
        exit;
    }
}
header("Location: ./");
exit;
?>