<?php
function ViewPointConfig($data, $templateFunc) {
    LoadView("page/class_main");
    ImportTemplate("page/class/point-config");
    ViewClassMain($data, fn()=> TemplatePointConfig($data, $templateFunc));
}
?>