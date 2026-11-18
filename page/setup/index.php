<?php
header("Cache-Control: no-store");
header("X-Frame-Options: DENY");

require_once __DIR__."/install.inc.php";

$locked = SetupIsLocked();
$hasSchema = SetupHasSchema();
$setupToken = "";
$eSetupApp = $_SESSION["setup_application"] ?? 0;

if (!$locked && $hasSchema) {
    $setupToken = $_SESSION["setup_token"] ??= bin2hex(random_bytes(32));

    if (!isset($_SESSION["setup_mysql"])) {
        $_SESSION["setup_mysql"] = [
            "main_options" => [
                "host" => "localhost",
                "port" => "3306",
                "database" => "klassku",
                "username" => "root",
                "password" => "",
            ],
            "user_options" => [
                "host" => "localhost",
                "port" => "3306",
                "database" => "klassku",
                "username" => "root",
                "password" => "",
            ],
        ];
    }
}
$apacheVersion = function_exists("apache_get_version") ? apache_get_version() : ($_SERVER["SERVER_SOFTWARE"] ?? "Tidak diketahui");

LoadView("page/setup");
$eSetupMysql = $_SESSION["setup_mysql"] ?? [];
$errorCode = $_GET["err"] ?? "";

if ($eSetupApp !== 2) {
    if ($locked) {
        $errorCode = "setup-locked";
    } elseif (!$hasSchema) {
        $errorCode = "schema-unavailable";
    }
}
$errorMsg = match ($errorCode) {
    "schema-unavailable" => "Setup tidak bisa dijalankan karena schema.sql tidak tersedia atau tidak bisa dibaca.",
    "invalid-setup-token" => "Sesi setup tidak valid. Coba kirim ulang formulir.",
    "invalid-host", "invalid-port", "invalid-database" => "Periksa host, port, dan nama database.",
    "invalid-username" => "Username MySQL tidak valid.",
    "error-connection" => "Koneksi MySQL gagal.",
    "database-check-failed" => "Database tidak bisa diperiksa.",
    "database-already-exists" => "Database sudah ada. Gunakan nama database lain.",
    "setup-locked" => "Setup terkunci. Pulihkan konfigurasi env untuk membuka Klassku.",
    "database-create-failed" => "Database gagal dibuat. Periksa izin installer dan nama database.",
    "setup-transaction-failed" => "Transaksi setup tidak bisa dimulai.",
    "user-level-create-failed" => "Level pengguna gagal dibuat.",
    "admin-create-failed" => "Akun admin gagal dibuat.",
    "schema-install-failed" => "Schema gagal dipasang. Periksa izin installer dan database yang sudah dibuat.",
    "env-write-failed" => "File env gagal disimpan. Periksa izin tulis.",
    "env-value-invalid" => "Konfigurasi MySQL harus ditulis dalam satu baris.",
    default => ""
};
$adminPassword = $eSetupApp === 2 ? ($_SESSION["setup_admin_password"] ?? null) : null;

if ($adminPassword !== null) {
    unset($_SESSION["setup_admin_password"]);
}
ViewSetup([
    "admin_password" => $adminPassword,
    "setup_app" => $eSetupApp,
    "mysql_version" => $_SESSION["setup_mysql_version"] ?? "Belum terhubung",
    "apache_version" => $apacheVersion,
    "env_file" => basename(SetupEnvFile()),
    "error_msg" => $errorMsg,
    "setup_mysql" => $eSetupMysql,
    "csrf_token" => $setupToken
]);
?>