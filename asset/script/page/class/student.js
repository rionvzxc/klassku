var DialogClassConfig = {
    "update": {
        dialog: { title: "Ubah Data Siswa" },
        form: { method: "POST", action: "update" }
    },
    "add": {
        dialog: { title: "Tambah Siswa" },
        form: { method: "POST", action: "add" }
    },
    "delete": {
        dialog: { title: "Hapus Siswa" },
        form: { method: "POST", action: "delete" }
    }
}
function DialogStudentForm(idDialog, configName, idClass) {
    let dialogConfig = DialogClassConfig[configName];

    if (idClass !== null) {
        let url = "get?id=" + encodeURIComponent(idClass);
        new XRequest(url)
        .SetResponseType("json")
        .OnSuccess(function(data) {
            if (data.length !== 0) {
                UpdateDialog(idDialog, dialogConfig, {
                    id: data[0].id,
                    name: data[0].name
                })
            }
        })
        .Send();
    } else {
        UpdateDialog(idDialog, dialogConfig, {})
    }
}
function DialogStudentConfirm(idDialog, configName, idStudent) {
    let dialogConfig = DialogClassConfig[configName];

    let url = "get?id=" + encodeURIComponent(idStudent);
    new XRequest(url)
    .SetResponseType("json")
    .OnSuccess(function(data) {
        if (data.length !== 0) {
            UpdateDialog(idDialog, dialogConfig, {
                id: data[0].id,
                message: `Apakah Anda ingin menghapus siswa "${data[0].name}"? ( permanen )`
            });
        }
    })
    .Send();
}