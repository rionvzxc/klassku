<?php
function ViewClassMain($data, $templateFunc) {
    LoadView("main");

    $urlPath = SplitRequestPath();

    if (count($urlPath) >= 3 && $urlPath[0] === "page" && $urlPath[1] === "class") {
        $data["page_nav"] = [
            "" => [
                "url" => "/class/",
                "label" => "<< Kembali"
            ],
            "assessment" => [
                "url" => "/class/assessment/",
                "label" => "Penilaian",
                "page_title" => "Penilaian"
            ],
            "agenda" => [
                "url" => "/class/agenda/",
                "label" => "Agenda",
                "page_title" => "Agenda"
            ],
            "student" => [
                "url" => "/class/student/",
                "label" => "Data Siswa",
                "page_title" => "Data Siswa"
            ],
            "rank" => [
                "url" => "/class/rank/",
                "label" => "Data Peringkat",
                "page_title" => "Data Peringkat"
            ],
            "point-config" => [
                "url" => "/class/point-config/",
                "label" => "Pengaturan Point",
                "page_title" => "Pengaturan Point"
            ]
        ];
        $data["page_nav_active"] = $urlPath[2];
    }
    ViewMain($data, $templateFunc);
}
?>
