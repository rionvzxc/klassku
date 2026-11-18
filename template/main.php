<?php function TemplateMain($data, $templateFunc) {
	$pageNav = $data["page_nav"] ?? [];
	$pageNavActive = $data["page_nav_active"] ?? "";
    $pageTitle = $data["page_title"] ?? $pageNav[$pageNavActive]["page_title"] ?? "";
    $theme = ($_SESSION["theme"] ?? "light") === "dark" ? "dark" : "light";
?>
<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Klassku</title>
	<link rel="stylesheet" href="/asset/style/main.css">
	<link rel="stylesheet" href="/asset/style/schoolapp.css">
	<link rel="stylesheet" href="/asset/style/simple-date-pick.css">
	<script src="/asset/script/simple-date-pick.js"></script>
	<script src="/asset/script/xrequest.js"></script>
	<script src="/asset/script/main.js" defer></script>
</head>
<body mode="<?= $theme ?>">
	<div class="app-layout" xflex="row" vstyle="fx-1">
		<div class="app-sidebar" xflex="col" vstyle="gp-20 pd-20 bg-1">
			<div class="app-brand" vstyle="fz-25 tx-bld">Klassku</div>
			<nav class="app-nav" xflex="col" vstyle="gp-5" aria-label="Menu utama">
				<?php foreach ($pageNav as $key => $nav): ?>
					<a href="<?= $nav["url"] ?>"<?= $pageNavActive === $key ? ' class="active" aria-current="page"' : "" ?>>
						<?= $nav["label"] ?>
					</a>
				<?php endforeach; ?>
			</nav>
		</div>
		<main class="app-main" xflex="col" vstyle="gp-15 pd-20 fx-1">
			<h1 id="page-title" vstyle="tx-bld"><?= $pageTitle ?></h1>
			<div id="page-content" xflex="col" vstyle="fx-1">
                <?php
                    if ($templateFunc != null) {
                        $templateFunc();
                    }
                ?>
            </div>
		</main>
	</div>
</body>
</html>
<?php } ?>
