<?php
function ViewLogin($data, $templateFunc) {
    ImportTemplate("page/login");
    ImportTemplate("dialog");

    TemplateLogin($data, function() use ($templateFunc, $data) {
        if ($templateFunc !== null) {
            $templateFunc();
        }
        TemplateDialog($data, null);
    });
}
?>