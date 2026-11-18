<?php
$eIdUser = $_SESSION["id_user"];
$eIdClass = $_SESSION["active_id_class"] ?? "";
$vRankName = $_POST["name"] ?? "";
$vPosition = intval($_POST["position"] ?? "");

$stmt = $MysqlDB->Prepare(
    "INSERT INTO `rank` (id_class, name, position, is_active) VALUES (?, ?, ?, ?)",
    [$eIdClass, $vRankName, $vPosition, 1]
);
if ($stmt && $stmt->rowCount() > 0) {
    header("Location: ./");
} else {
    header("Location: ./?err=add");
}
exit;
?>