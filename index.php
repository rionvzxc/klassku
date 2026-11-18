<?php
require_once __DIR__."/engine/loader.php";
$pageFile = null;

// TODO: Cek
if ($UrlPath !== "" && $UrlPath !== "index.php") {
    $pageRoot = __DIR__."/page";
    $targetPath = $pageRoot."/".$UrlPath;
    $validPath = true;

    if (is_dir($targetPath)) {
        $targetPath = $targetPath."/index.php";
    } elseif (!is_file($targetPath)) {
        $targetPath = $targetPath.".php";
    }
    if (!is_file($targetPath) || !str_ends_with($targetPath, ".php") || str_ends_with($targetPath, ".inc.php")) {
        ViewError([
            "code" => "404",
            "message" => "Periksa kembali alamat halaman."
        ], null);
        exit;
    }
    $pageFile = $targetPath;
}
if ($pageFile !== null) {
    require_once $pageFile;
    exit;
}
ImportTemplate("dashboard");
LoadView("main");
ViewMain([
    "page_title" => "Dashboard"
], fn()=>
    TemplateDashboard([], null)
);
?>
