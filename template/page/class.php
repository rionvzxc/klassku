<?php function TemplateClass($data) {
    $resClasses = $data["res_classes"] ?? [];
    $vClassName = htmlspecialchars($data["v_class_name"] ?? "");
    
    $blankDataMessage = $vClassName ?
        "Kelas tidak ditemukan." : "Data Kelas masih kosong.";
?>
<div xflex="col" vstyle="fx-1 gp-15">
    <div xflex="row" vstyle="gp-10 pd-10 bg-1">
        <form xgrid-cf="cf3 a0 b1 c0" vstyle="fx-1 gp-10 al-i5" method="GET">
            <div>Nama Kelas :</div>
            <input type="text" name="name" value="<?= $vClassName ?>" placeholder="Cari Nama Kelas">
            <button>Cari</button>
        </form>
        <div xline-vr></div>
        <button zdialog="open:add-class" data-callback="DialogClassForm" data-args="'add',null">
            Tambah Kelas
        </button>
    </div>
    <div vstyle="fx-1" style="overflow: auto;">
        <table vstyle="bg-1">
            <colgroup>
                <col style="width: 1%">
                <col>
                <col style="width: 1%">
                <col style="width: 1%">
                <col style="width: 1%">
            </colgroup>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Kelas</th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($resClasses) == 0): ?>
                    <tr><td colspan="5" vstyle="pd-10">
                        <?= $blankDataMessage ?>
                    </td></tr>
                <?php else: ?>
                    <?php foreach ($resClasses as $index => $class): ?>
                    <?php $idClass = htmlspecialchars($class["id"]); ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($class["name"]) ?></td>
                            <td>
                                <form method="GET" action="access">
                                    <input type="hidden" name="id" value="<?= $idClass ?>">
                                    <button>Akses Kelas</button>
                                </form>
                            </td>
                            <td>
                                <button zdialog="open:add-class"
                                    data-callback="DialogClassForm" data-args="'update','<?= $idClass ?>'">
                                    Ubah
                                </button>
                            </td>
                            <td>
                                <button xbutton="bt-r" zdialog="open:delete-class"
                                    data-callback="DialogClassConfirm" data-args="'delete','<?= $idClass ?>'">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<script src="/asset/script/page/class.js" defer></script>
<?php } ?>