<?php
$eIdClass = $_SESSION["active_id_class"] ?? "";

$vAttendType = $_POST["attend_type"] ?? 2;
$vAttendMvalue = $_POST["attend_mvalue"] ?? 0;

$vAssignType = $_POST["assign_type"] ?? 2;
$vAssignMvalue = $_POST["assign_mvalue"] ?? 0;

$vPartcpType = $_POST["partcp_type"] ?? 2;
$vPartcpMvalue = $_POST["partcp_mvalue"] ?? 0;

// Validasi type.
$vAttendType = in_array((int)$vAttendType, [1, 2], true) ? (int)$vAttendType : 2;
$vAssignType = in_array((int)$vAssignType, [1, 2], true) ? (int)$vAssignType : 2;
$vPartcpType = in_array((int)$vPartcpType, [1, 2], true) ? (int)$vPartcpType : 2;

// Susun konfigurasi JSON.
$vAttendConfig = json_encode([
    "type" => $vAttendType,
    "mvalue" => (int)$vAttendMvalue
]);
$vAssignConfig = json_encode([
    "type" => $vAssignType,
    "mvalue" => (int)$vAssignMvalue
]);
$vPartcpConfig = json_encode([
    "type" => $vPartcpType,
    "mvalue" => (int)$vPartcpMvalue
]);

// Ambil konfigurasi berdasarkan kelas.
$stmtGet = $MysqlDB->Prepare(
    "SELECT id, attendance, assignment, participation FROM point_config WHERE id_class = ? LIMIT 1",
    [$eIdClass]
);
$resPointCfg = $stmtGet ? $stmtGet->fetch() : false;

if ($resPointCfg) {
    $vIdPointCfg = $resPointCfg["id"];

    $stmtUpdate = $MysqlDB->Prepare(
        "UPDATE point_config SET attendance = ?, assignment = ?, participation = ?
        WHERE id = ? AND id_class = ?",
        [$vAttendConfig, $vAssignConfig, $vPartcpConfig, $vIdPointCfg, $eIdClass]
    );
} else {
    $stmtUpdate = $MysqlDB->Prepare(
        "INSERT INTO point_config (id_class, attendance, assignment, participation)
        VALUES (?, ?, ?, ?)",
        [$eIdClass, $vAttendConfig, $vAssignConfig, $vPartcpConfig]
    );
}
if ($stmtUpdate && $stmtUpdate->rowCount() > 0) {
    header("Location: ./");
    exit;
}
header("Location: ./?err=point-config-error");
exit;
?>