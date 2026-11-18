<?php
$eIdUser = $_SESSION["id_user"];
$vClassName = $_GET["name"] ?? "";
$resClasses = [];
$stmt = $MysqlDB->Prepare(
    "SELECT id, name FROM class WHERE id_user = ? AND name LIKE ? AND is_active = 1 ORDER BY name ASC",
    [$eIdUser, "%".$vClassName."%"]
);
$resClasses = $stmt ? $stmt->fetchAll() : [];

ImportTemplate("form/page/class");
ImportTemplate("form/main");
$resDialogs = [
    [
        "id" => "add-class",
        "template_func" => fn() => TemplateClassFormAdd([])
    ],[
        "id" => "delete-class",
        "template_func" => fn() => TemplateMainDialogDelete([])
    ]
];
LoadView("page/class");
ViewClass([
    "v_class_name" => $vClassName,
    "res_dialogs" => $resDialogs,
    "res_classes" => $resClasses
], null);
?>