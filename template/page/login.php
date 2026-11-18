<?php function TemplateLogin($data, $templateFunc) {
    $username = htmlspecialchars($data["username"] ?? "");
    $errMsg = htmlspecialchars($data["error_message"] ?? "");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klassku</title>
	<link rel="stylesheet" href="/asset/style/main.css">
</head>
<body xflex="row" vstyle="al-i5">
    <div xflex="col" vstyle="fx-1 al-i5">
        <div class="login-card" xflex="col" vstyle="gp-15 pd-25 al-i5 bg-1">
            <div vstyle="fz-25 tx-bld">Klassku</div>
            <div xline-hr></div>
            <?php if ($errMsg !== ""): ?>
                <div><?= $errMsg ?></div>
            <?php endif; ?>
            <form xgrid-cf="cf2 a0 b1" vstyle="gp-10 al-i5" method="POST">
                <div>Username : </div>
                <input type="text" name="username" value="<?= $username ?>">
                <div>Password : </div>
                <input type="password" name="password">
                <div></div>
                <button>Login</button>
            </form>
        </div>
    </div>
</body>
</html>
<?php } ?>
