<?php function TemplateAssessment($data, $templateFunc) {
    $vDate = htmlspecialchars($data["v_date"] ?? "");
    $resPointCfg = $data["res_point-config"] ?? [];
    $resAgenda = $data["res_agenda"] ?? [];
    $resAgendas = $data["res_agendas"] ?? [];
    $resAssessments = $data["res_assessments"] ?? [];

    $attendType = intval($resPointCfg["attendance"]["type"] ?? 2);
    $attendMvalue = intval($resPointCfg["attendance"]["mvalue"] ?? 0);

    $assignType = intval($resPointCfg["assignment"]["type"] ?? 2);
    $assignMvalue = intval($resPointCfg["assignment"]["mvalue"] ?? 0);

    $partcpType = intval($resPointCfg["participation"]["type"] ?? 2);
    $partcpMvalue = intval($resPointCfg["participation"]["mvalue"] ?? 0);

    $idAgenda = htmlspecialchars($resAgenda["id"] ?? "");
    $meetingCount = intval($resAgenda["meeting_count"] ?? 1);
?>
<div class="assessment" xflex="col" vstyle="fx-1 gp-15">
    <div xflex="row" vstyle="gp-10 pd-10 bg-1">
        <form xflex="row" vstyle="gp-10 al-i5" method="GET">
            <div>Tanggal Agenda :</div>
            <input type="text" id="assessment-date" name="date" value="<?= $vDate ?>">
            <button>Tampilkan</button>
        </form>
        <?php if (count($resAgendas) > 1 || ($resAgendas && !$resAgenda)): ?>
            <form xflex="row" vstyle="gp-10 al-i5" method="GET">
                <input type="hidden" name="date" value="<?= $vDate ?>">
                <label for="assessment-agenda">Agenda:</label>
                <select id="assessment-agenda" name="id_agenda">
                    <?php foreach ($resAgendas as $agenda): ?>
                        <option value="<?= htmlspecialchars($agenda["id"]) ?>"
                            <?= $agenda["id"] === ($resAgenda["id"] ?? null) ? "selected" : "" ?>>
                            <?= htmlspecialchars($agenda["name"]) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button>Pilih Agenda</button>
            </form>
        <?php endif; ?>
        <?php if ($resAgenda): ?>
            <div xline-vr></div>
            <div xflex="row" vstyle="gp-10">
                <button class="btn-edit-all"
                    zaction data-callback="AssessmentEditor" data-args="null">
                    Ubah Penilaian
                </button>
                <button xbutton="bt-g" class="btn-update-all"
                    zaction data-callback="AssessmentUpdate" data-args="'<?= $idAgenda ?>', null">
                    Simpan Perubahan
                </button>
            </div>
        <?php endif; ?>
    </div>
    <p id="assessment-save-error" role="alert" hidden>Penilaian gagal disimpan. Silakan coba lagi.</p>
    <div vstyle="fx-1" style="overflow: auto;">
        <table vstyle="bg-1">
            <colgroup>
                <col style="width: 1%">
                <col>
                <?php for ($n = 1; $n <= $meetingCount * 3; $n++): ?>
                    <col style="width: 1%">
                <?php endfor; ?>
                <col>
                <col>
            </colgroup>
            <thead>
                <tr>
                    <th rowspan="3">No</th>
                    <th rowspan="3">Nama Siswa</th>
                    <th colspan="<?= 3 * $meetingCount ?>">Point</th>
                    <th rowspan="3">Total Nilai</th>
                    <th rowspan="3">Peringkat</th>
                    <!-- <th rowspan="3"></th> -->
                </tr>
                <tr>
                    <th colspan="<?= $meetingCount ?>">Kehadiran (x<?= $attendMvalue ?>)</th>
                    <th colspan="<?= $meetingCount ?>">Tugas (x<?= $assignMvalue ?>)</th>
                    <th colspan="<?= $meetingCount ?>">Keaktifan (x<?= $partcpMvalue ?>)</th>
                </tr>
                <tr>
                <?php for ($i = 1; $i <= 3; $i++):
                    for ($n = 1; $n <= $meetingCount; $n++):
                ?>
                    <th><?= $n ?></th>
                <?php endfor; endfor; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($resAssessments as $index => $assessment):
                    $idStudent = htmlspecialchars($assessment["id_student"] ?? "");
                ?>
                    <tr data-id-student="<?= $idStudent ?>">
                        <td><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($assessment["student_name"]) ?></td>
                        <?php
                            $assessmentTypes = [
                                "attendance" => $attendType,
                                "assignment" => $assignType,
                                "participation" => $partcpType
                            ];
                            foreach ($assessmentTypes as $name => $type):
                                foreach ($assessment[$name] as $iPoint => $value):
                                    $value = intval($value);
                        ?>
                            <td class="td-input">
                                <?php if ($type === 1): ?>
                                    <div xcheck="<?= $value === 1 ? 's1' : 's0' ?>" editable="false"
                                        data-name="<?= $name ?>"
                                        data-index="<?= $iPoint ?>"
                                        data-value="<?= $value ?>">
                                        <?= $value === 1 ? "✓" : "-" ?>
                                    </div>
                                <?php else: ?>
                                    <div xinput contenteditable="false"
                                        data-name="<?= $name ?>"
                                        data-index="<?= $iPoint ?>"
                                        data-value="<?= $value ?>"><?= $value ?></div>
                                <?php endif; ?>
                            </td>
                        <?php endforeach;
                            endforeach;
                        ?>
                        <td><?= $assessment["point_total"] ?></td>
                        <td><?= $assessment["rank"] ?></td>
                        <!-- <td>
                            <button class="btn-edit"
                                zaction data-callback="AssessmentEditor" data-args="'<?= $idStudent ?>'">
                                Ubah
                            </button>
                        </td> -->
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<script src="/asset/script/page/class/assessment.js"></script>
<?php } ?>
