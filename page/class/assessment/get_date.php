<?php
header("Content-Type: application/json");

$eIdClass = $_SESSION["active_id_class"] ?? "";
$vMonth = $_GET["month"] ?? "";

if (!is_string($vMonth) || !preg_match("/\\A[0-9]{4}-(0[1-9]|1[0-2])\\z/", $vMonth)) {
    http_response_code(400);
    echo json_encode(["success" => false, "dates" => []]);
    exit;
}
$vStartDate = $vMonth."-01";
$vEndDate = (new DateTime($vStartDate))->modify("+1 month")->format("Y-m-d");

$stmt = $MysqlDB->Prepare(
    "SELECT DISTINCT `date`
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