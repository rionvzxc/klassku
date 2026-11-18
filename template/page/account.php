<?php function TemplateAccount($data) {
    $resUser = $data["res_user"] ?? [];

    $fullName = htmlspecialchars($resUser["name"] ?? "");
    $username = htmlspecialchars($resUser["username"] ?? "");
    $userLevel = htmlspecialchars($resUser["user_level"] ?? "");
    $theme = ($_SESSION["theme"] ?? "light") === "dark" ? "dark" : "light";
?>
<div xflex="col" vstyle="fx-1 gp-15">
    <div xflex="col" vstyle="gp-10 pd-15 bg-1">
        <div xflex="row">
            <form xgrid-cf="cf2 a0 b0" vstyle="gp-10 al-i5" action="update" method="POST">
                <div>Nama Lengkap :</div>
                <input type="text" name="name" value="<?= $fullName ?>">
                <div>Username :</div>
                <input type="text" name="username" value="<?= $username ?>">
                <div>Level :</div>
                <input type="text" value="<?= $userLevel ?>" disabled>
                <div></div>
                <button>Simpan Perubahan</button>
            </form>
        </div>
        <div xline-hr></div>
        <div>
            <button zdialog="open:update-password" data-callback="DialogAccountPasswordForm">
                Ubah Password
            </button>
        </div>
        <div>
            <a xbutton href="logout">Logout</a>
        </div>
    </div>
    <form xflex="col" vstyle="gp-10 pd-15 bg-1" method="POST" action="update-theme">
        <fieldset class="theme-options">
            <legend vstyle="tx-bld">Warna tema</legend>
            <div xflex="row" vstyle="gp-10">
                <label><input type="radio" name="theme" value="light"<?= $theme === "light" ? " checked" : "" ?>> Terang</label>
                <label><input type="radio" name="theme" value="dark"<?= $theme === "dark" ? " checked" : "" ?>> Gelap</label>
            </div>
        </fieldset>
        <div><button type="submit">Simpan Tema</button></div>
    </form>
</div>
<script src="/asset/script/page/account.js" defer></script>
<?php } ?>
