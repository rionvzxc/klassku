<?php function TemplateError($data, $templateFunc) {
    $code = $data["code"] ?? "";
    $name = $data["name"] ?? "";
    $message = $data["message"] ?? "";
    $theme = ($_SESSION["theme"] ?? "light") === "dark" ? "dark" : "light";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error <?= $code ?> <?= $name ?></title>
	<link rel="stylesheet" href="/asset/style/main.css">
    <style>
        .error-code {
            font-size: 100px;
            font-weight: bold;
            line-height: 1;
        }
    </style>
</head>
<body mode="<?= $theme ?>" xflex="row" vstyle="al-i5">
    <div xflex="col" vstyle="gp-10 fx-1 al-i5">
        <div class="error-code"><?= $code ?></div>
        <div>
            <b><?= $name ?>:</b> <?= $message ?>
        </div>
        <div>
            <a xbutton href="/">Kembali ke Beranda</a>
        </div>
    </div>
</body>
</html>
<?php } ?>
