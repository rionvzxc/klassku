<?php
$eIdUser = $_SESSION["id_user"];
$eIdClass = $_SESSION["active_id_class"] ?? "";
$vIdStudent = $_POST["id"] ?? "";

$MysqlDB->BeginTransaction(true); // true = Auto Rollback

$stmt = $MysqlDB->Prepare(
    "DELETE FROM assessment WHERE id_student = ?",
    [$vIdStudent]
);
if (!$stmt) {
    header("Location: ./?err=delete");
    exit;
}
$stmt = $MysqlDB->Prepare(
    "DELETE FROM student WHERE id_class = ? AND id = ?",
    [$eIdClass, $vIdStudent]
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