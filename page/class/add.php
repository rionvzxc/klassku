<?php
$eIdUser = $_SESSION["id_user"];
$vClassName = $_POST["name"] ?? "";

$stmt = $MysqlDB->Prepare("INSERT INTO class (id_user, name, is_active) VALUES (?, ?, ?)",
    [$eIdUser, $vClassName, 1]
);
if ($stmt && $stmt->rowCount() > 0) {
    header("Location: ./");
} else {
    header("Location: ./?err=add");
}
?>