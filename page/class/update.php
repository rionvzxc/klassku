<?php
$eIdUser = $_SESSION["id_user"];
$vIdClass = $_POST["id"] ?? "";
$vName = $_POST["name"] ?? "";

$stmt = $MysqlDB->Prepare("UPDATE class SET name = ? WHERE id_user = ? AND id = ?",
    [$vName, $eIdUser, $vIdClass]
);
if ($stmt && $stmt->rowCount() > 0) {
    header("Location: ./");
} else {
    header("Location: ./?err=update");
}
?>