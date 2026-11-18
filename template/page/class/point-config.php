<?php function TemplatePointConfig($data, $templateFunc) {
    $resPointCfg = $data["res_point-config"] ?? [];
    $pointConfigList = [
        [
            "name" => "Kehadiran",
            "type_name" => "attend_type",
            "mvalue_name" => "attend_mvalue",
            "type" => $resPointCfg["attendance"]["type"] ?? 1,
            "mvalue" => $resPointCfg["attendance"]["mvalue"] ?? 0
        ],
        [
            "name" => "Tugas",
            "type_name" => "assign_type",
            "mvalue_name" => "assign_mvalue",
            "type" => $resPointCfg["assignment"]["type"] ?? 2,
            "mvalue" => $resPointCfg["assignment"]["mvalue"] ?? 0
        ],
        [
            "name" => "Keaktifan",
            "type_name" => "partcp_type",
            "mvalue_name" => "partcp_mvalue",
            "type" => $resPointCfg["participation"]["type"] ?? 2,
            "mvalue" => $resPointCfg["participation"]["mvalue"] ?? 0
        ]
    ];
?>
<div xflex="col" vstyle="fx-1 gp-15">
    <form xflex="col" vstyle="gp-10" action="update" method="POST">
        <div class="page-panel" xflex="row" vstyle="gp-10 pd-10 bg-1">
            <button>
                Simpan Pengaturan
            </button>
        </div>
        <div class="page-panel page-table">
            <table vstyle="bg-1">
                <colgroup>
                    <col>
                    <col>
                    <col style="width: 1%">
                </colgroup>
                <thead>
                    <tr>
                        <th>Penilaian</th>
                        <th>Tipe</th>
                        <th>Bobot Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pointConfigList as $vAssessment) { ?>
                    <tr>
                        <td><?= $vAssessment["name"] ?></td>
                        <td>
                            <div xflex="row" vstyle="gp-10">
                                <label xflex="row" vstyle="gp-5 al-i5">
                                    <input type="radio" name="<?= $vAssessment["type_name"] ?>" value="1"
                                        <?= $vAssessment["type"] == 1 ? "checked" : "" ?>
                                    >
                                    Centang
                                </label>
                                <label xflex="row" vstyle="gp-5 al-i5">
                                    <input type="radio" name="<?= $vAssessment["type_name"] ?>" value="2"
                                        <?= $vAssessment["type"] == 2 ? "checked" : "" ?>
                                    >
                                    Penilaian Angka
                                </label>
                            </div>
                        </td>
                        <td>
                            <input type="number" name="<?= $vAssessment["mvalue_name"] ?>"
                                value="<?= $vAssessment["mvalue"] ?>">
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </form>
</div>
<?php } ?>
