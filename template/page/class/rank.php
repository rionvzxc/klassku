<?php function TemplateRank($data, $templateFunc) {
    $vRankName = htmlspecialchars($data["v_rank_name"] ?? "");
    $resRanks = $data["res_ranks"] ?? [];
    $pagination = $data["pagination"];

    $blankDataMessage = $vRankName ?
        "Peringkat tidak ditemukan." : "Data Peringkat masih kosong.";
?>
<div xflex="col" vstyle="fx-1 gp-15">
    <div xflex="row" vstyle="gp-10 pd-10 bg-1">
        <form xgrid-cf="cf3 a0 b1 c0" vstyle="fx-1 gp-10 al-i5" method="GET">
            <div>Nama Peringkat :</div>
            <input type="text" name="name" value="<?= $vRankName ?>" placeholder="Cari Nama Peringkat">
            <button>Cari</button>
        </form>
        <div xline-vr></div>
        <button zdialog="open:add-rank" data-callback="DialogRankForm" data-args="'add',null">
            Tambah Peringkat
        </button>
    </div>
    <div vstyle="fx-1" style="overflow: auto;">
        <table vstyle="bg-1">
            <colgroup>
                <col style="width: 1%">
                <col>
                <col>
                <col style="width: 1%">
                <col style="width: 1%">
            </colgroup>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Peringkat</th>
                    <th>Urutan</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($resRanks) == 0): ?>
                    <tr><td colspan="5" vstyle="pd-10">
                        <?= $blankDataMessage ?>
                    </td></tr>
                <?php else: ?>
                    <?php
                        foreach ($resRanks as $index => $rank):
                        $idRank = htmlspecialchars($rank["id"] ?? "");
                    ?>
                        <tr>
                            <td><?= $pagination["offset"] + $index + 1 ?></td>
                            <td><?= htmlspecialchars($rank["name"]) ?></td>
                            <td><?= htmlspecialchars($rank["position"]) ?></td>
                            <td>
                                <button zdialog="open:add-rank"
                                    data-callback="DialogRankForm" data-args="'update','<?= $idRank ?>'">
                                    Ubah
                                </button>
                            </td>
                            <td>
                                <button xbutton="bt-r" zdialog="open:delete-rank"
                                    data-callback="DialogRankConfirm" data-args="'delete','<?= $idRank ?>'">
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
<script src="/asset/script/page/class/rank.js"></script>
<?php } ?>
