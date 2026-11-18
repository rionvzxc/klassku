<?php
ImportUtil("agenda");

$eIdUser = $_SESSION["id_user"];
$eIdClass = $_SESSION["active_id_class"] ?? "";
$vIdAgenda = $_POST["id"] ?? "";
$vAgendaName = $_POST["name"] ?? "";
$vDate = $_POST["date"] ?? "";
$vMeetingCount = intval($_POST["meeting_count"] ?? "");

$agendaError = SaveAgendaOnUniqueDate($MysqlDB, $eIdUser, $eIdClass, $vDate, $vIdAgenda, function() use (
    $MysqlDB, $vAgendaName, $vDate, $vMeetingCount, $eIdClass, $vIdAgenda
) {
    $stmt = $MysqlDB->Prepare(
        "UPDATE agenda SET name = ?, date = ?, meeting_count = ? WHERE id_class = ? AND id = ?",
        [$vAgendaName, $vDate, $vMeetingCount, $eIdClass, $vIdAgenda]
    );

    return $stmt && $stmt->rowCount() > 0;
});

if ($agendaError === "") {
    header("Location: ./");
} else {
    header("Location: ./?err=".($agendaError === "failed" ? "update" : $agendaError));
}
?>
