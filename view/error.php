<?php
function ViewError($data, $templateFunc) {
    ImportTemplate("error");

    $errorList = [
        "400" => "Bad Request",
        "401" => "Unauthorized",
        "403" => "Forbidden",
        "404" => "Page Not Found"
    ];
    $code = $data["code"] ?? "";
    $data["name"] = $errorList[$code] ?? "";

    if (isset($errorList[$code])) {
        http_response_code((int)$code);
    }
    TemplateError($data, $templateFunc);
    exit;
}
?>
