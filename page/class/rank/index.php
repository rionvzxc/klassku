<?php
ImportUtil("date_format");
ImportUtil("pagination");

$eIdUser = $_SESSION["id_user"];
$eIdClass = $_SESSION["active_id_class"] ?? "";
$eClassName = $_SESSION["active_class_name"] ?? "";
$vName = $_GET["name"] ?? "";
$vName = is_string($vName) ? $vName : "";

$pagination = PaginateList(
    $MysqlDB, "id, name, position", "FROM `rank` WHERE id_class = ? AND is_active = 1 AND name LIKE ?",
    "position ASC, id ASC", [$eIdClass, "%".$vName."%"], ["name" => $vName]
);

ImportTemplate("pagination");
ImportTemplate("form/page/class/rank");
ImportTemplate("form/main");
$resDialogs = [
    [
        "id" => "add-rank",
        "template_func" => fn() => TemplateRankFormAdd([])
    ],[
        "id" => "delete-rank",
        "template_func" => fn() => TemplateMainDialogDelete([])
    ]
];
LoadView("page/class/rank");
ViewRank([
    "page_title" => "Data Peringkat : Kelas \"".$eClassName."\"",
    "res_dialogs" => $resDialogs,
    "v_rank_name" => $vName,
    "res_ranks" => $pagination["items"],
    "pagination" => $pagination
], null);
?>
