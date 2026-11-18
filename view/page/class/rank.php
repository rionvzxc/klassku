<?php
function ViewRank($data, $templateFunc) {
    LoadView("page/class_main");
    ImportTemplate("page/class/rank");
    ViewClassMain($data, fn()=> TemplateRank($data, $templateFunc));
}
?>