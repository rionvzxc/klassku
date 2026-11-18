<?php
header("Content-Type: application/json; charset=utf-8");
$eIdUser = $_SESSION["id_user"];
$eIdClass = $_SESSION["active_id_class"] ?? "";
$vRequest = json_decode(file_get_contents("php://input"), true);
$vIdAgenda = $vRequest["id_agenda"] ?? "";
$vPoints = $vRequest["points"] ?? [];

if ($vIdAgenda === "" || !is_array($vPoints)) {
    http_response_code(400);
    echo json_encode(["error" => "invalid-data"]);
    exit;
}
$stmtAgenda = $MysqlDB->Prepare(
    "SELECT agenda.id FROM agenda
    INNER JOIN class ON class.id = agenda.id_class
    WHERE agenda.id = ? AND agenda.id_class = ? AND agenda.is_active = 1
    AND class.id_user = ? AND class.is_active = 1 LIMIT 1",
    [$vIdAgenda, $eIdClass, $eIdUser]
);

if (!$stmtAgenda) {
    http_response_code(500);
    echo json_encode(["error" => "update-error"]);
    exit;
}
if (!$stmtAgenda->fetch()) {
    http_response_code(404);
    echo json_encode(["error" => "agenda-not-found"]);
    exit;
}
if (!$MysqlDB->BeginTransaction()) {
    http_response_code(500);
    echo json_encode(["error" => "transaction-error"]);
    exit;
}

foreach ($vPoints as $point) {
    $vIdStudent = $point["id_student"] ?? "";

    if ($vIdStudent === "") {
        $MysqlDB->RollBack();
        http_response_code(400);
        echo json_encode(["error" => "invalid-student"]);
        exit;
    }
    $stmtStudent = $MysqlDB->Prepare("SELECT id FROM student WHERE id = ? AND id_class = ? LIMIT 1",
        [$vIdStudent, $eIdClass]
    );

    if (!$stmtStudent || !$stmtStudent->fetch()) {
        if ($MysqlDB->InTransaction()) {
            $MysqlDB->RollBack();
        }
        http_response_code($stmtStudent ? 404 : 500);
        echo json_encode(["error" => $stmtStudent ? "student-not-found" : "update-error"]);
        exit;
    }
    $vStudentPoints = [];

    foreach (["attendance", "assignment", "participation"] as $key) {
        $values = $point[$key] ?? [];

        if (!is_array($values)) {
            $values = [];
        }
        $values = array_map("intval", $values);
        $vStudentPoints[$key] = implode(",", $values);
    }
    $stmtAssessment = $MysqlDB->Prepare(
        "SELECT id FROM assessment WHERE id_student = ? AND id_agenda = ? LIMIT 1",
        [$vIdStudent, $vIdAgenda]
    );

    if (!$stmtAssessment) {
        http_response_code(500);
        echo json_encode(["error" => "update-error"]);
        exit;
    }
    if ($stmtAssessment->fetch()) {
        $resUpdate = $MysqlDB->Prepare(
            "UPDATE assessment SET attendance = ?, assignment = ?, participation = ?
            WHERE id_student = ? AND id_agenda = ?",
            [
                $vStudentPoints["attendance"],
                $vStudentPoints["assignment"],
                $vStudentPoints["participation"],
                $vIdStudent,
                $vIdAgenda
            ]);
    } else {
        $resUpdate = $MysqlDB->Prepare(
            "INSERT INTO assessment (id_student, id_agenda, attendance, assignment, participation)
            VALUES (?, ?, ?, ?, ?)",
            [
                $vIdStudent,
                $vIdAgenda,
                $vStudentPoints["attendance"],
                $vStudentPoints["assignment"],
                $vStudentPoints["participation"]
            ]);
    }
    if (!$resUpdate) {
        http_response_code(500);
        echo json_encode(["error" => "update-error"]);
        exit;
    }
}
if ($MysqlDB->InTransaction()) {
    if (!$MysqlDB->Commit()) {
        http_response_code(500);
        echo json_encode(["error" => "commit-error"]);
        exit;
    }
} else {
    http_response_code(500);
    echo json_encode(["error" => "transaction-rolled-back"]);
    exit;
}

// var_dump([
//     "in_transaction" => $MysqlDB->InTransaction(),
//     "commit" => $MysqlDB->Commit(),
//     "in_transaction_after_commit" => $MysqlDB->InTransaction()
// ]);

echo json_encode(["success" => true]);
?>
