<?php
function ViewClass($data, $templateFunc) {
    LoadView("main");
    ImportTemplate("page/class");
    ViewMain($data, fn()=> TemplateClass($data, $templateFunc));
}
?>