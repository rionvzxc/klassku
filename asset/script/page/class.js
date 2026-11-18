var DialogClassConfig = {
    "update": {
        dialog: { title: "Ubah Kelas" },
        form: { method: "POST", action: "update" }
    },
    "add": {
        dialog: { title: "Tambah Kelas" },
        form: { method: "POST", action: "add" }
    },
    "delete": {
        dialog: { title: "Hapus Kelas" },
        form: { method: "POST", action: "delete" }
    }
}
function DialogClassForm(idDialog, configName, idClass) {
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
function DialogClassConfirm(idDialog, configName, idClass) {
    let dialogConfig = DialogClassConfig[configName];

    let url = "get?id=" + encodeURIComponent(idClass);
    new XRequest(url)
    .SetResponseType("json")
    .OnSuccess(function(data) {
        if (data.length !== 0) {
            UpdateDialog(idDialog, dialogConfig, {
                id: data[0].id,
                message: `Apakah Anda ingin menghapus kelas "${data[0].name}"? ( permanen )`
            });
        }
    })
    .Send();
}