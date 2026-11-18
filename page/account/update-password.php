<?php
$eIdUser = $_SESSION["id_user"];
$oldPassword = $_POST["old_password"] ?? "";
$newPassword = $_POST["new_password"] ?? "";

if ($oldPassword === "" || $newPassword === "") {
    header("Location: ./?err=empty-field");
    exit;
}
$stmt = $MysqlDB->Prepare(
    "SELECT id, password FROM user WHERE id = ? AND is_active = 1 LIMIT 1",
    [$eIdUser]
);
$resUser = $stmt ? $stmt->fetch() : false;

if (!$resUser) {
    header("Location: ./?err=account-inactive");
    exit;
}
if (!password_verify($oldPassword, $resUser["password"])) {
    header("Location: ./?err=wrong-old-password");
    exit;
}
$hashPassword = password_hash($newPassword, PASSWORD_BCRYPT);
$stmt = $MysqlDB->Prepare(
    "UPDATE user SET password = ? WHERE id = ? AND is_active = 1",
    [$hashPassword, $eIdUser]
);
if (!$stmt) {
    header("Location: ./?err=update-failed");
    exit;
}
header("Location: ./");
exit;
?>