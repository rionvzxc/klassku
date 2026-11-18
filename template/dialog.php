<?php function TemplateDialog($data) {
    $resDialogs = $data["res_dialogs"] ?? [];
?>
<div xdialog-area>
    <?php foreach ($resDialogs as $dialog):
        $dialogTitle = htmlspecialchars($dialog["title"] ?? "");
        $idDialog = htmlspecialchars($dialog["id"] ?? "");
        $templateFunc = $dialog["template_func"] ?? null;
    ?>
        <div xdialog id="<?= $idDialog ?>" vstyle="bg-1">
            <div xflex="col">
                <div xflex="row" vstyle="gp-10 pd-10 al-i5">
                    <div vid="dialog-title" vstyle="fz-20 tx-bld fx-1"><?= $dialogTitle ?></div>
                    <div xbutton zdialog="close:<?= $idDialog ?>">X</div>
                </div>
                <div xline-hr></div>
                <div xflex="col" vstyle="pd-15">
                    <?php if ($templateFunc !== null) {
                        $templateFunc();
                    } ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php } ?>