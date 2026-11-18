<?php
if (!str_starts_with($UrlPath, "login")) {
    // User Validator
    UseMiddleware("user");
    
    // Class Validator
    if (preg_match("/^class\\/(agenda|assessment|point-config|rank|student)(\\/|$)/i", $UrlPath)) {
        UseMiddleware("class");
    }
}
?>
