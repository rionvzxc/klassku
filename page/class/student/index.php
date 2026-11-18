<?php
ImportUtil("pagination");

$eIdUser = $_SESSION["id_user"];
$eIdClass = $_SESSION["active_id_class"] ?? "";
$eClassName = $_SESSION["active_class_name"] ?? "";
$vStudentName = $_GET["name"] ?? "";
$vStudentName = is_string($vStudentName) ? $vStudentName : "";

$pagination = PaginateList(
    $MysqlDB, "id, name", "FROM student WHERE id_class = ? AND name LIKE ? AND is_active = 1",
    "name ASC, id ASC", [$eIdClass, "%".$vStudentName."%"], ["name" => $vStudentName]
);

ImportTemplate("pagination");
ImportTemplate("form/page/class/student");
ImportTemplate("form/main");
$resDialogs = [
    [
        "id" => "add-student",
        "template_func" => fn() => TemplateStudentFormAdd([])
    ],[
        "id" => "update-student",
        "template_func" => fn() => TemplateStudentFormUpdate([])
    ],[
        "id" => "delete-student",
        "template_func" => fn() => TemplateMainDialogDelete([])
    ]
];
LoadView("page/class/student");
ViewStudent([
    "page_title" => "Data Siswa : Kelas \"".$eClassName."\"",
    "v_student_name" => $vStudentName,
    "res_dialogs" => $resDialogs,
    "res_students" => $pagination["items"],
    "pagination" => $pagination
], null);
?>
