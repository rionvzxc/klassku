<?php function TemplateStudentFormAdd($data) {
    $formAction = htmlspecialchars($data["form"]["action"] ?? "");
    $formMethod = htmlspecialchars($data["form"]["method"] ?? "");
?>
<form xgrid-cf="cf2 a0 b1" vstyle="gp-10 al-i1" action="<?= $formAction ?>" method="<?= $formMethod ?>">
    <div>
        <div style="height: 40px; line-height: 40px;">Nama Siswa :</div>
    </div>
    <textarea name="name"></textarea>
    <div></div>
    <div>Catatan: Tambahkan nama kedalam "baris baru" untuk menambahkan banyak nama sekaligus.</div>
    <div></div>
    <button>Simpan</button>
</form>
<?php } ?>

<?php function TemplateStudentFormUpdate($data) {
    $formAction = htmlspecialchars($data["form"]["action"] ?? "");
    $formMethod = htmlspecialchars($data["form"]["method"] ?? "");
?>
<form xgrid-cf="cf2 a0 b1" vstyle="gp-10 al-i5" action="<?= $formAction ?>" method="<?= $formMethod ?>">
    <input type="hidden" name="id">

    <div>Nama Siswa :</div>
    <input type="text" name="name"></textarea>
    <div></div>
    <button>Simpan</button>
</form>
<?php } ?>