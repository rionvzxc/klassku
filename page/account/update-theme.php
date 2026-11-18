<?php
$theme = $_POST["theme"] ?? "";

if (!in_array($theme, ["light", "dark"], true)) {
    http_response_code(400);
    exit;
}
$_SESSION["theme"] = $theme;
header("Location: ./", true, 303);
exit;
