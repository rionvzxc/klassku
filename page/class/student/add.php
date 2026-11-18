<?php
$eIdUser = $_SESSION["id_user"];
$eIdClass = $_SESSION["active_id_class"] ?? "";
$vName = $_POST["name"] ?? "";
$studentNames = explode("\n", str_replace("\r", "", $vName));

$qValues = [];
$qPholders = [];

foreach ($studentNames as $name) {
    $name = trim($name);

    if ($name === "") {
        continue;
    }
    $qPholders[] = "(?, ?, ?)";
    $qValues[] = $eIdClass;
    $qValues[] = $name;
    $qValues[] = 1;
}
if (count($qValues) > 0) {
    $stmt = $MysqlDB->Prepare(
        "INSERT INTO student (id_class, name, is_active) VALUES ".implode(",", $qPholders),
        $qValues
    );
    if ($stmt && $stmt->rowCount() > 0) {
        header("Location: ./");
    } else {
        header("Location: ./?err=add");
    }
} else {
    header("Location: ./?err=add");
}
?>