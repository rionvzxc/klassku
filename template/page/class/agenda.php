<?php function TemplateAgenda($data, $templateFunc) {
    $resAgendas = $data["res_agendas"] ?? [];
    $pagination = $data["pagination"];
    $filters = $pagination["filters"];
    $months = [
        1 => "Januari", "Februari", "Maret", "April", "Mei", "Juni",
        "Juli", "Agustus", "September", "Oktober", "November", "Desember"
    ];
    $agendaError = match ($data["agenda_error"] ?? "") {
        "duplicate-date" => "Tanggal tersebut sudah dipakai oleh agenda lain di kelas ini.",
        "invalid-date" => "Masukkan tanggal agenda yang valid.",
        "add" => "Agenda gagal ditambahkan.",
        "update" => "Agenda gagal diperbarui.",
        default => ""
    };

    $blankDataMessage = $filters ?
        "Agenda tidak ditemukan." : "Data Agenda masih kosong.";
?>
<div xflex="col" vstyle="fx-1 gp-15">
    <div xflex="row" vstyle="gp-10 pd-10 bg-1 al-i9" style="flex-wrap: wrap;">
        <form xgrid-cf="cf5 a0 b0 c0 d0 e0" vstyle="gp-10 al-i5" style="flex-wrap: wrap;" method="GET">
            <label>Nama agenda</label>
            <label>Tanggal</label>
            <label>Bulan</label>
            <label>Tahun</label>
            <label></label>
            <input type="search" name="name" value="<?= htmlspecialchars($filters["name"] ?? "") ?>">
            <input type="number" name="day" min="1" max="31" value="<?= $filters["day"] ?? "" ?>">
            <select name="month">
                <option value="">Semua bulan</option>
                <?php foreach ($months as $number => $month): ?>
                    <option value="<?= $number ?>"<?= ($filters["month"] ?? null) === $number ? " selected" : "" ?>>
                        <?= $month ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="number" name="year" min="1000" max="9999" value="<?= $filters["year"] ?? "" ?>">
            <button>Cari</button>
        </form>
        <div xline-vr></div>
        <button zdialog="open:add-agenda" data-callback="DialogAgendaForm" data-args="'add',null">
            Buat Agenda Baru
        </button>
    </div>
    <?php if ($agendaError !== ""): ?>
        <p role="alert"><?= $agendaError ?></p>
    <?php endif; ?>
    <div vstyle="fx-1" style="overflow: auto;">
        <table vstyle="bg-1">
            <colgroup>
                <col style="width: 1%">
                <col>
                <col>
                <col style="width: 1%">
                <col style="width: 1%">
                <col style="width: 1%">
            </colgroup>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Agenda</th>
                    <th>Tanggal</th>
                    <th>Jumlah Pertemuan</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($resAgendas) == 0): ?>
                    <tr><td colspan="6" vstyle="pd-10">
                        <?= $blankDataMessage ?>
                    </td></tr>
                <?php else: ?>
                    <?php foreach ($resAgendas as $index => $agenda):
                        $idAgenda = htmlspecialchars($agenda["id"]);
                        $agendaDate = htmlspecialchars(DateF($agenda["date"]));
                    ?>
                        <tr>
                            <td><?= $pagination["offset"] + $index + 1 ?></td>
                            <td><?= htmlspecialchars($agenda["name"]) ?></td>
                            <td><?= $agendaDate ?></td>
                            <td><?= htmlspecialchars($agenda["meeting_count"]) ?></td>
                            <td>
                                <button zdialog="open:add-agenda"
                                    data-callback="DialogAgendaForm" data-args="'update','<?= $idAgenda ?>'">
                                    Ubah
                                </button>
                            </td>
                            <td>
                                <button xbutton="bt-r" zdialog="open:delete-agenda"
                                    data-callback="DialogAgendaConfirm" data-args="'delete','<?= $idAgenda ?>'">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php TemplatePagination($pagination); ?>
</div>
<script src="/asset/script/page/class/agenda.js"></script>
<?php } ?>
