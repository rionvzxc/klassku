<?php
$_SESSION["setup_application"] = max(0, ($_SESSION["setup_application"] ?? 0) - 1);
header("Location: /setup", true, 303);
exit;
?>