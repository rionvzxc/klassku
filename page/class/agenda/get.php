<?php
header("Content-Type: application/json; charset=utf-8");

$eIdUser = $_SESSION["id_user"];
$eIdClass = $_SESSION["active_id_class"] ?? "";
$vIdAgenda = $_GET["id"] ?? "";

$vPage = intval($_GET["page"] ?? 1);
$vPageItem = intval($_GET["page_item"] ?? 10);
$vOffset = ($vPage - 1) * $vPageItem;

if ($vIdAgenda !== "") { // Get By Id
    $stmt = $MysqlDB->Prepare(
        "SELECT id, name, date, meeting_count FROM agenda WHERE id_class = ? AND id = ? AND is_active = 1
        LIMIT ? OFFSET ?",
        [$eIdClass, $vIdAgenda, $vPageItem, $vOffset]
    );
} else { // Get All
    $stmt = $MysqlDB->Prepare(
        "SELECT id, name, date, meeting_count FROM agenda WHERE id_class = ? AND is_active = 1
        LIMIT ? OFFSET ?",
        [$eIdClass, $vPageItem, $vOffset]
    );
}
$resAgendas = $stmt ? $stmt->fetchAll() : [];

echo json_encode($resAgendas);
?>