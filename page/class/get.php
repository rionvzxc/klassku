<?php
header("Content-Type: application/json; charset=utf-8");

$eIdUser = $_SESSION["id_user"];
$vIdClass = $_GET["id"] ?? "";

$vPage = intval($_GET["page"] ?? 1);
$vPageItem = intval($_GET["page_item"] ?? 10);
$vOffset = ($vPage - 1) * $vPageItem;

$stmt = $MysqlDB->Prepare(
    "SELECT id, name FROM class WHERE id_user = ? AND id = ? AND is_active = 1
    LIMIT ? OFFSET ?",
    [$eIdUser, $vIdClass, $vPageItem, $vOffset]
);
$resClasses = $stmt ? $stmt->fetchAll() : [];

echo json_encode($resClasses);
?>