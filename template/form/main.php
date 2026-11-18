<?php function TemplateMainDialogDelete($data) {
    $formAction = htmlspecialchars($data["form"]["action"] ?? "");
    $formMethod = htmlspecialchars($data["form"]["method"] ?? "");
?>
<form xflex="col" vstyle="gp-15 al-i5" action="<?= $formAction ?>" method="<?= $formMethod ?>">
    <input type="hidden" name="id">

    <label name="message"></label>
    <div>
        <button>Hapus</button>
    </div>
</form>
<?php } ?>