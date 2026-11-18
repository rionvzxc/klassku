<?php
function ViewAgenda($data, $templateFunc) {
    LoadView("page/class_main");
    ImportTemplate("page/class/agenda");
    ViewClassMain($data, fn()=> TemplateAgenda($data, $templateFunc));
}
?>