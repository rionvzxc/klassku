<?php
require_once __DIR__."/database.php";
?>

<?php
global $MysqlDB;

$options = [
    "host"     => $options["host"] ?? ($_ENV["MYSQL_HOST"] ?? ""),
    "port"     => $options["port"] ?? ($_ENV["MYSQL_PORT"] ?? ""),
    "database" => $options["database"] ?? ($_ENV["MYSQL_DATABASE"] ?? ""),
    "username" => $options["username"] ?? ($_ENV["MYSQL_USERNAME"] ?? ""),
    "password" => $options["password"] ?? ($_ENV["MYSQL_PASSWORD"] ?? "")
];

$MysqlDB = new MysqlDatabase($options);
?>