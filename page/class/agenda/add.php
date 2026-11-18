<?php
ImportUtil("agenda");

$eIdUser = $_SESSION["id_user"];
$eIdClass = $_SESSION["active_id_class"] ?? "";
$vAgendaName = $_POST["name"] ?? "";
$vDate = $_POST["date"] ?? "";
$vMeetingCount = intval($_POST["meeting_count"] ?? "");

$agendaError = SaveAgendaOnUniqueDate($MysqlDB, $eIdUser, $eIdClass, $vDate, 0, function() use (
    $MysqlDB, $eIdClass, $vAgendaName, $vDate, $vMeetingCount
) {
    return $MysqlDB->Prepare(
        "INSERT INTO agenda (id_class, name, date, meeting_count, is_active) VALUES (?, ?, ?, ?, ?)",
        [$eIdClass, $vAgendaName, $vDate, $vMeetingCount, 1]
    );
});

if ($agendaError === "") {
    header("Location: ./");
} else {
    header("Location: ./?err=".($agendaError === "failed" ? "add" : $agendaError));
}
?>
