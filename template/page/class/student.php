<?php function TemplateStudent($data, $templateFunc) {
    $vStudentName = htmlspecialchars($data["v_student_name"] ?? "");
    $resStudents = $data["res_students"] ?? [];
    $pagination = $data["pagination"];

    $blankDataMessage = $vStudentName ?
        "Siswa tidak ditemukan." : "Data Siswa masih kosong.";
?>
<div xflex="col" vstyle="fx-1 gp-15">
    <div xflex="row" vstyle="gp-10 pd-10 bg-1">
        <form xgrid-cf="cf3 a0 b1 c0" vstyle="fx-1 gp-10 al-i5" method="GET">
            <div>Nama Siswa :</div>
            <input type="text" name="name" value="<?= $vStudentName ?>" placeholder="Cari Nama Siswa">
            <button>Cari</button>
        </form>
        <div xline-vr></div>
        <button zdialog="open:add-student" data-callback="DialogStudentForm" data-args="'add',null">
            Tambah Siswa
        </button>
    </div>
    <div vstyle="fx-1" style="overflow: auto;">
        <table vstyle="bg-1">
            <colgroup>
                <col style="width: 1%">
                <col>
                <col style="width: 1%">
                <col style="width: 1%">
            </colgroup>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Siswa</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($resStudents) == 0): ?>
                    <tr><td colspan="4" vstyle="pd-10">
                        <?= $blankDataMessage ?>
                    </td></tr>
                <?php else: ?>
                    <?php
                        foreach ($resStudents as $index => $student):
                        $idStudent = htmlspecialchars($student["id"] ?? "");
                    ?>
                        <tr>
                            <td><?= $pagination["offset"] + $index + 1 ?></td>
                            <td><?= htmlspecialchars($student["name"]) ?></td>
                            <td>
                                <button zdialog="open:update-student"
                                    data-callback="DialogStudentForm" data-args="'update','<?= $idStudent ?>'">
                                    Ubah
                                </button>
                            </td>
                            <td>
                                <button xbutton="bt-r" zdialog="open:delete-student"
                                    data-callback="DialogStudentConfirm" data-args="'delete','<?= $idStudent ?>'">
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
<script src="/asset/script/page/class/student.js"></script>
<?php } ?>
