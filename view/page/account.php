<?php
function ViewAccount($data, $templateFunc) {
    LoadView("main");
    ImportTemplate("page/account");
    ViewMain($data, fn()=> TemplateAccount($data, $templateFunc));
}
?>