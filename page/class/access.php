<?php
$eIdUser = $_SESSION["id_user"];
$vIdClass = $_GET["id"] ?? "";

$stmt = $MysqlDB->Prepare(
    "SELECT id, name FROM class WHERE id_user = ? AND id = ? AND is_active = 1 LIMIT 1",
    [$eIdUser, $vIdClass]
);
$resClass = $stmt ? $stmt->fetch() : false;

if ($resClass) {
    $_SESSION["active_id_class"] = $vIdClass;
    $_SESSION["active_class_name"] = $resClass["name"];

    header("Location: ./student/");
} else {
    ViewError([
        "code" => "400",
        "message" => "ID kelas tidak valid."
    ], null);
}
exit;
?>
