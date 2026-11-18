<?php function TemplateAgendaFormAdd($data) {
    $formAction = htmlspecialchars($data["form"]["action"] ?? "");
    $formMethod = htmlspecialchars($data["form"]["method"] ?? "");
?>
<form xgrid-cf="cf2 a0 b1" vstyle="gp-10 al-i5" action="<?= $formAction ?>" method="<?= $formMethod ?>">
    <input type="hidden" name="id">

    <div>Nama Agenda : </div>
    <input type="text" name="name">
    <div>Tanggal : </div>
    <input type="text" id="date" name="date">
    <div>Jumlah Pertemuan : </div>
    <input type="text" name="meeting_count">
    <div></div>
    <button>Simpan</button>
</form>
<?php } ?>