<?php
ImportUtil("date_format");
ImportUtil("pagination");

$eIdUser = $_SESSION["id_user"];
$eIdClass = $_SESSION["active_id_class"] ?? "";
$eClassName = $_SESSION["active_class_name"] ?? "";

$vName = $_GET["name"] ?? "";
$vName = is_string($vName) ? trim($vName) : "";
$filters = ["name" => $vName];
$fromWhere = "FROM agenda WHERE id_class = ? AND is_active = 1";
$params = [$eIdClass];

if ($vName !== "") {
    $fromWhere .= " AND name LIKE ?";
    $params[] = "%".$vName."%";
}
foreach ([
    "day" => ["DAY", 1, 31],
    "month" => ["MONTH", 1, 12],
    "year" => ["YEAR", 1000, 9999]
] as $key => [$datePart, $minimum, $maximum]) {
    $input = $_GET[$key] ?? "";
    $value = is_string($input) && ctype_digit($input) ? (int)$input : 0;

    if ($value >= $minimum && $value <= $maximum) {
        $filters[$key] = $value;
        $fromWhere .= " AND ".$datePart."(date) = ?";
        $params[] = $value;
    }
}
$pagination = PaginateList(
    $MysqlDB, "id, name, date, meeting_count", $fromWhere, "date DESC, id DESC", $params, $filters
);

ImportTemplate("pagination");
ImportTemplate("form/page/class/agenda");
ImportTemplate("form/main");
$resDialogs = [
    [
        "id" => "add-agenda",
        "template_func" => fn() => TemplateAgendaFormAdd([])
    ],[
        "id" => "delete-agenda",
        "template_func" => fn() => TemplateMainDialogDelete([])
    ]
];
LoadView("page/class/agenda");
ViewAgenda([
    "page_title" => "Agenda : Kelas \"".$eClassName."\"",
    "res_dialogs" => $resDialogs,
    "res_agendas" => $pagination["items"],
    "agenda_error" => $_GET["err"] ?? "",
    "pagination" => $pagination
], null);
?>
