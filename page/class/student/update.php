<?php
$eIdUser = $_SESSION["id_user"];
$eIdClass = $_SESSION["active_id_class"] ?? "";
$vIdStudent = $_POST["id"] ?? "";
$vName = $_POST["name"] ?? "";

$stmt = $MysqlDB->Prepare("UPDATE student SET name = ? WHERE id_class = ? AND id = ?",
    [$vName, $eIdClass, $vIdStudent]
);
if ($stmt && $stmt->rowCount() > 0) {
    header("Location: ./");
} else {
    header("Location: ./?err=update");
}
?>