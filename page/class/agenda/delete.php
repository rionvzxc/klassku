<?php
$eIdUser = $_SESSION["id_user"];
$eIdClass = $_SESSION["active_id_class"] ?? "";
$vIdAgenda = $_POST["id"] ?? "";

$MysqlDB->BeginTransaction(true);

$stmt = $MysqlDB->Prepare(
    "DELETE FROM assessment WHERE id_agenda = ?",
    [$vIdAgenda]
);
if (!$stmt) {
    header("Location: ./?err=delete");
    exit;
}
$stmt = $MysqlDB->Prepare(
    "DELETE FROM agenda WHERE id_class = ? AND id = ?",
    [$eIdClass, $vIdAgenda]
);
if (!$stmt || $stmt->rowCount() === 0) {
    header("Location: ./?err=delete");
    exit;
}
if (!$MysqlDB->Commit()) {
    header("Location: ./?err=delete");
    exit;
}
header("Location: ./");
exit;
?>