<?php
$eIdUser = $_SESSION["id_user"];
$eIdClass = $_SESSION["active_id_class"] ?? "";
$vIdRank = $_POST["id"] ?? "";

$stmt = $MysqlDB->Prepare(
    "DELETE FROM `rank` WHERE id_class = ? AND id = ?",
    [$eIdClass, $vIdRank]
);
if ($stmt && $stmt->rowCount() > 0) {
    header("Location: ./");
} else {
    header("Location: ./?err=delete");
}
exit;
?>