<?php
$eIdUser = $_SESSION["id_user"];
$vIdClass = $_POST["id"] ?? "";

$MysqlDB->BeginTransaction(true);

$stmt = $MysqlDB->Prepare(
    "SELECT id FROM class WHERE id_user = ? AND id = ? LIMIT 1",
    [$eIdUser, $vIdClass]
);
if (!$stmt || !$stmt->fetch()) {
    header("Location: ./?err=delete");
    exit;
}
$stmt = $MysqlDB->Prepare(
    "DELETE FROM assessment
    WHERE id_student IN (SELECT id FROM student WHERE id_class = ?)
    OR id_agenda IN (SELECT id FROM agenda WHERE id_class = ?)",
    [$vIdClass, $vIdClass]
);
if (!$stmt) {
    header("Location: ./?err=delete");
    exit;
}
$stmt = $MysqlDB->Prepare(
    "DELETE FROM point_config WHERE id_class = ?",
    [$vIdClass]
);
if (!$stmt) {
    header("Location: ./?err=delete");
    exit;
}
$stmt = $MysqlDB->Prepare(
    "DELETE FROM `rank` WHERE id_class = ?",
    [$vIdClass]
);
if (!$stmt) {
    header("Location: ./?err=delete");
    exit;
}
$stmt = $MysqlDB->Prepare(
    "DELETE FROM student WHERE id_class = ?",
    [$vIdClass]
);
if (!$stmt) {
    header("Location: ./?err=delete");
    exit;
}
$stmt = $MysqlDB->Prepare(
    "DELETE FROM agenda WHERE id_class = ?",
    [$vIdClass]
);
if (!$stmt) {
    header("Location: ./?err=delete");
    exit;
}
$stmt = $MysqlDB->Prepare(
    "DELETE FROM class WHERE id_user = ? AND id = ?",
    [$eIdUser, $vIdClass]
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