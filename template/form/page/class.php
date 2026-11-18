<?php function TemplateClassFormAdd($data) {
    $formAction = htmlspecialchars($data["form"]["action"] ?? "");
    $formMethod = htmlspecialchars($data["form"]["method"] ?? "");
?>
<form xgrid-cf="cf2 a0 b1" vstyle="gp-10 al-i5" action="<?= $formAction ?>" method="<?= $formMethod ?>">
    <input type="hidden" name="id">

    <div>Nama Kelas : </div>
    <input type="text" name="name">
    <div></div>
    <button>Simpan</button>
</form>
<?php } ?>