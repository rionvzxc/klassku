<?php
function ViewAssessment($data, $templateFunc) {
    LoadView("page/class_main");
    ImportTemplate("page/class/assessment");
    ViewClassMain($data, fn()=> TemplateAssessment($data, $templateFunc));
}
?>