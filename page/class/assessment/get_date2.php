<?php
header("Content-Type: application/json");

$eIdClass = $_SESSION["active_id_class"] ?? "";
$vMonth = $_GET["month"] ?? "";

if ($eIdClass === "" || !preg_match("/^\\d{4}-(0[1-9]|1[0-2])$/", $vMonth)) {
    echo json_encode([
        "success" => false,
        "dates" => []
    ]);
    exit;
}

$vStartDate = $vMonth."-01";
$vEndDate = (new DateTime($vStartDate))->modify("+1 month")->format("Y-m-d");

$stmt = $MysqlDB->Prepare(
    "SELECT DISTINCT DATE_FORMAT(`date`, '%Y-%m-%d') AS agenda_date
    FROM agenda
    WHERE id_class = ? AND `date` >= ? AND `date` < ?
    ORDER BY `date` ASC",
    [$eIdClass, $vStartDate, $vEndDate]
);

if (!$stmt) {
    echo json_encode([
        "success" => false,
        "dates" => []
    ]);
    exit;
}

$resDates = $stmt->fetchAll(PDO::FETCH_COLUMN);

echo json_encode([
    "success" => true,
    "dates" => $resDates
]);
exit;
?>
