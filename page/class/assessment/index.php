<?php
ImportUtil("date_format");

$eIdUser = $_SESSION["id_user"];
$eIdClass = $_SESSION["active_id_class"] ?? "";
$eClassName = $_SESSION["active_class_name"] ?? "";
$vDate = $_GET["date"] ?? "";
$vIdAgenda = $_GET["id_agenda"] ?? "";

if ($vDate === "") {
    $stmtLatestDate = $MysqlDB->Prepare(
        "SELECT date FROM agenda WHERE id_class = ? AND is_active = 1 ORDER BY date DESC LIMIT 1",
        [$eIdClass]
    );
    $vDate = $stmtLatestDate ? ($stmtLatestDate->fetchColumn() ?: "") : "";
}
$stmtAgendas = $MysqlDB->Prepare(
    "SELECT id, name, date, meeting_count FROM agenda
    WHERE id_class = ? AND is_active = 1 AND date = NULLIF(?, '')
    ORDER BY id DESC",
    [$eIdClass, $vDate]
);
$resAgendas = $stmtAgendas ? $stmtAgendas->fetchAll() : [];
$resAgenda = $vIdAgenda === "" ? ($resAgendas[0] ?? []) : [];

foreach ($resAgendas as $agenda) {
    if ((string)$agenda["id"] === $vIdAgenda) {
        $resAgenda = $agenda;
        break;
    }
}
$resPointCfg = [];
$resAssessments = [];

if ($resAgenda) {
    $vDate = $resAgenda["date"];
    $idAgenda = $resAgenda["id"];
    $meetingCount = intval($resAgenda["meeting_count"]);

    $resPointCfg = $MysqlDB->Prepare(
        "SELECT id, attendance, assignment, participation FROM point_config
        WHERE id_class = ? ORDER BY id ASC LIMIT 1",
        [$eIdClass]
    )->fetch();

    if ($resPointCfg) {
        $resPointCfg["attendance"] = json_decode($resPointCfg["attendance"], true);
        $resPointCfg["assignment"] = json_decode($resPointCfg["assignment"], true);
        $resPointCfg["participation"] = json_decode($resPointCfg["participation"], true);
    }

    $attendMvalue = intval($resPointCfg["attendance"]["mvalue"] ?? 0);
    $assignMvalue = intval($resPointCfg["assignment"]["mvalue"] ?? 0);
    $partcpMvalue = intval($resPointCfg["participation"]["mvalue"] ?? 0);

    $resAssessments = $MysqlDB->Prepare(
        "SELECT
            student.id AS id_student,
            student.name AS student_name,
            COALESCE(assessment.attendance, '') AS attendance,
            COALESCE(assessment.assignment, '') AS assignment,
            COALESCE(assessment.participation, '') AS participation
        FROM student
        LEFT JOIN assessment
            ON assessment.id_student = student.id
            AND assessment.id_agenda = ?
        WHERE id_class = ?",
        [$idAgenda, $eIdClass]
    )->fetchAll();
    
    $resRanks = $MysqlDB->Prepare(
        "SELECT id, name, position FROM `rank` WHERE id_class = ? AND is_active = 1 ORDER BY position ASC",
        [$eIdClass]
    )->fetchAll();

    for ($i = 0; $i < count($resAssessments); $i++) {
        foreach (["attendance", "assignment", "participation"] as $key) {
            $values = explode(",", $resAssessments[$i][$key]);
            $values = array_pad($values, $meetingCount, 0);
            $resAssessments[$i][$key] = array_slice($values, 0, $meetingCount);
        }
        $attendPoint = array_sum($resAssessments[$i]["attendance"]) * $attendMvalue;
        $assignPoint = array_sum($resAssessments[$i]["assignment"]) * $assignMvalue;
        $partcpPoint = array_sum($resAssessments[$i]["participation"]) * $partcpMvalue;
        $resAssessments[$i]["point_total"] = $attendPoint + $assignPoint + $partcpPoint;
        $resAssessments[$i]["rank"] = "";
    }
    usort($resAssessments, function($a, $b) {
        return $b["point_total"] <=> $a["point_total"];
    });
    for ($i = 0; $i < count($resRanks) && $i < count($resAssessments); $i++) {
        $resAssessments[$i]["rank"] = $resRanks[$i]["name"];
    }
}
LoadView("page/class/assessment");
ViewAssessment([
    "page_title" => "Penilaian : Kelas \"".$eClassName."\" pada ".DateF($vDate),
    "res_point-config" => $resPointCfg,
    "res_assessments" => $resAssessments,
    "res_agenda" => $resAgenda,
    "res_agendas" => $resAgendas,
    "v_date" => $vDate
], null);
?>
