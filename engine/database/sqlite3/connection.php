<?php
require_once __DIR__."/database.php";
?>

<?php
global $Sqlite3DB;

$sqlitePath = $_ENV["SQLITE3_DATABASE"] ?? __DIR__."/schoolapp.sqlite3";

if ($sqlitePath !== ":memory:" && !preg_match("/\\A(?:[a-zA-Z]:)?[\\/\\\\]/", $sqlitePath)) {
    $sqlitePath = dirname(__DIR__, 3)."/".$sqlitePath;
}
$Sqlite3DB = new Sqlite3Database(["database" => $sqlitePath]);
?>
