<?php function TemplateSetup($data, $templateFunc) {
    [
        "setup_app" => $setupApp,
        "admin_password" => $adminPassword,
        "mysql_version" => $mysqlVersion,
        "env_file" => $envFile,
        "error_msg" => $errorMsg,
        "setup_mysql" => $setupMysql,
        "apache_version" => $apacheVersion,
        "csrf_token" => $csrfToken
    ] = $data;
    $mainOptions = $setupMysql["main_options"] ?? [];
    $userOptions = $setupMysql["user_options"] ?? [];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Setup Klassku</title>
    <link rel="stylesheet" href="/asset/style/main.css">
</head>
<body xflex="col" vstyle="al-i5 pd-15">
<div class="login-card" xflex="col" vstyle="gp-15 pd-20 bg-1">
    <div vstyle="fz-25 tx-bld">Setup Klassku</div>
    <div xgrid-cf="cf3 a1 b1 c1" vstyle="gp-5" aria-label="Progres instalasi">
        <?php foreach ([1 => "Akun Installer", "Akun Aplikasi", "Selesai"] as $number => $label): ?>
            <div xflex="row" vstyle="gp-5 al-i5"<?= $number === $setupApp + 1 ? ' aria-current="step"' : "" ?>>
                <?php if ($number > 1): ?>
                    <div vstyle="fg-2" aria-hidden="true">—</div>
                <?php endif; ?>
                <div xflex="col" vstyle="fx-1 al-i5 gp-5 <?= $number === $setupApp + 1 ? 'pd-5 bd-1 rd-5 bg-2 tx-bld' : 'pd-5 fg-2' ?>">
                    <div><?= $number ?></div>
                    <div><?= $label ?></div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div xflex="col" vstyle="gp-5">
        <div>PHP : <?= EscapeHtml(PHP_VERSION) ?></div>
        <div>Apache : <?= EscapeHtml($apacheVersion) ?></div>
        <div>MySQL : <?= EscapeHtml($mysqlVersion) ?></div>
        <div>Env : <?= EscapeHtml($envFile) ?></div>
    </div>
    <?php if ($setupApp === 2): ?>
        <div vstyle="tx-bld">Klassku berhasil dipasang.</div>
        <?php if ($adminPassword !== null): ?>
            <div>Username awal : admin</div>
            <div>Password awal : <?= EscapeHtml($adminPassword) ?></div>
        <?php endif; ?>
        <div><a xbutton href="/">Kembali ke Beranda</a></div>
    <?php else: ?>
        <?php if ($errorMsg !== ""): ?><div vstyle="tx-bld" role="alert"><?= EscapeHtml($errorMsg) ?></div><?php endif; ?>
        <?php if ($csrfToken !== "" && $setupApp === 0): ?>
        <div vstyle="fz-20">Akun MySQL installer</div>
        <div>Gunakan akun yang boleh membuat database, tabel, dan data awal.</div>
        <form xflex="col" vstyle="gp-10" method="post" action="/setup/update-main_config">
            <input type="hidden" name="csrf_token" value="<?= EscapeHtml($csrfToken) ?>">
            <label>Host MySQL</label>
            <input name="host" value="<?= EscapeHtml($mainOptions["host"] ?? "") ?>" required>
            <label>Port</label>
            <input type="number" name="port" min="1" max="65535" value="<?= EscapeHtml($mainOptions["port"] ?? "") ?>" required>
            <label>Nama database</label>
            <input name="database" value="<?= EscapeHtml($mainOptions["database"] ?? "") ?>" maxlength="64" required>
            <label>Username installer</label>
            <input name="username" value="<?= EscapeHtml($mainOptions["username"] ?? "") ?>" required>
            <label>Password installer</label>
            <input type="password" name="password" autocomplete="off">
            <div xflex="row" vstyle="gp-5">
                <button>Cek Koneksi dan Lanjut</button>
            </div>
        </form>
        <?php elseif ($csrfToken !== "" && $setupApp === 1): ?>
        <div vstyle="fz-20">Akun MySQL aplikasi</div>
        <div>Gunakan akun lain dengan izin SELECT, INSERT, UPDATE, dan DELETE pada database tujuan. Akun ini akan disimpan di <?= EscapeHtml($envFile) ?> setelah instalasi berhasil.</div>
        <div>
            Database tujuan 
            <label vstyle="tx-bld"><?= EscapeHtml($userOptions["database"] ?? "") ?></label> 
            di
            <label vstyle="tx-bld"><?= EscapeHtml($userOptions["host"] ?? "") ?>:<?= EscapeHtml($userOptions["port"] ?? "") ?></label>
        </div>
        <form xflex="col" vstyle="gp-10" method="post" action="/setup/update-user_config">
            <input type="hidden" name="csrf_token" value="<?= EscapeHtml($csrfToken) ?>">
            <label>Username aplikasi</label>
            <input name="username" value="<?= EscapeHtml($userOptions["username"] ?? "") ?>" required>
            <label>Password aplikasi</label>
            <input type="password" name="password" autocomplete="off">
            <div xflex="row" vstyle="gp-5">
                <a xbutton="bt-g" href="/setup/back">Kembali</a>
                <button>Pasang Klassku</button>
            </div>
        </form>
        <?php endif; ?>
    <?php endif; ?>
</div>
</body>
</html>
<?php } ?>