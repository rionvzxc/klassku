<?php
$eIdUser = $_SESSION["id_user"] ?? "";
$eIdClass = $_SESSION["active_id_class"] ?? "";

$resClass = $MysqlDB->Prepare(
    "SELECT id, name FROM class WHERE id_user = ? AND id = ? AND is_active = 1 LIMIT 1",
    [$eIdUser, $eIdClass]
)->fetch();

if (!$resClass) {
    ViewError([
        "code" => "403",
        "message" => "ID kelas tidak valid."
    ], null);
    exit;
}
?>