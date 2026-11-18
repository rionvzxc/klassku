<?php
$eIdUser = $_SESSION["id_user"];

$resUser = [];
$stmt = $MysqlDB->Prepare(
    "SELECT user.id, user.name, user.username, user_level.name AS user_level FROM user
    INNER JOIN user_level ON user.id_user_level = user_level.id
    WHERE user.id = ? AND user.is_active = 1
    LIMIT 1",
    [$eIdUser]
);
if ($stmt) {
    $resUser = $stmt->fetch();
}
ImportTemplate("form/page/account");
$resDialogs = [
    [
        "id" => "update-password",
        "template_func" => fn() => TemplateAccountFormUpdatePassword([])
    ]
];
LoadView("page/account");
ViewAccount([
    "res_dialogs" => $resDialogs,
    "res_user" => $resUser
], null);
?>