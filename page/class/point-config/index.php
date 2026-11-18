<?php
ImportUtil("date_format");

$eIdUser = $_SESSION["id_user"];
$eIdClass = $_SESSION["active_id_class"] ?? "";
$eClassName = $_SESSION["active_class_name"] ?? "";

$stmt = $MysqlDB->Prepare(
    "SELECT id, attendance, assignment, participation FROM point_config WHERE id_class = ? LIMIT 1",
    [$eIdClass]
);
$resPointCfg = $stmt ? $stmt->fetch() : false;

if ($resPointCfg) {
    $resPointCfg["attendance"] = json_decode($resPointCfg["attendance"], true);
    $resPointCfg["assignment"] = json_decode($resPointCfg["assignment"], true);
    $resPointCfg["participation"] = json_decode($resPointCfg["participation"], true);
}

LoadView("page/class/point-config");
ViewPointConfig([
    "page_title" => "Pengaturan Point : Kelas \"".$eClassName."\"",
    "res_point-config" => $resPointCfg
], null);
?>