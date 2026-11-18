<?php
function ViewMain($data, $templateFunc) {
    ImportTemplate("main");
    ImportTemplate("dialog");
    
    if (!isset($data["page_nav"])) {
        $data["page_nav"] = [
            "" => [
                "url" => "/",
                "label" => "Dashboard",
                "page_title" => "Dashboard"
            ],
            "class" => [
                "url" => "/class/",
                "label" => "Kelas",
                "page_title" => "Kelas"
            ],
            "account" => [
                "url" => "/account/",
                "label" => "Akun",
                "page_title" => "Akun"
            ]
        ];
        
        $urlPath = SplitRequestPath();

        if (count($urlPath) >= 2 && $urlPath[0] === "page") {
            $data["page_nav_active"] = $urlPath[1];
        }
    }
    TemplateMain($data, function() use ($templateFunc, $data) {
        if ($templateFunc !== null) {
            $templateFunc();
        }
        TemplateDialog($data, null);
    });
}
?>
