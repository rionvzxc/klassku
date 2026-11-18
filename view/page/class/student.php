<?php
function ViewStudent($data, $templateFunc) {
    LoadView("page/class_main");
    ImportTemplate("page/class/student");
    ViewClassMain($data, fn()=> TemplateStudent($data, $templateFunc));
}
?>