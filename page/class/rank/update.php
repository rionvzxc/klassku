<?php
$eIdUser = $_SESSION["id_user"];
$eIdClass = $_SESSION["active_id_class"] ?? "";
$vIdRank = $_POST["id"] ?? "";
$vRankName = $_POST["name"] ?? "";
$vPosition = intval($_POST["position"] ?? "");

$stmt = $MysqlDB->Prepare(
    "UPDATE `rank` SET name = ?, position = ? WHERE id_class = ? AND id = ?",
    [$vRankName, $vPosition, $eIdClass, $vIdRank]
);
if ($stmt && $stmt->rowCount() > 0) {
    header("Location: ./");
} else {
    header("Location: ./?err=update");
}
?>