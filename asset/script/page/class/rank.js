var DialogClassConfig = {
    "update": {
        dialog: { title: "Ubah Data Peringkat" },
        form: { method: "POST", action: "update" }
    },
    "add": {
        dialog: { title: "Tambah Peringkat" },
        form: { method: "POST", action: "add" }
    },
    "delete": {
        dialog: { title: "Hapus Peringkat" },
        form: { method: "POST", action: "delete" }
    }
}
function DialogRankForm(idDialog, configName, idRank) {
    let dialogConfig = DialogClassConfig[configName];

    if (idRank !== null) {
        let url = "get?id=" + encodeURIComponent(idRank);
        new XRequest(url)
        .SetResponseType("json")
        .OnSuccess(function(data) {
            if (data.length !== 0) {
                UpdateDialog(idDialog, dialogConfig, {
                    id: data[0].id,
                    name: data[0].name,
                    position: data[0].position
                });
            }
        })
        .Send();
    } else {
        UpdateDialog(idDialog, dialogConfig, {});
    }
}
function DialogRankConfirm(idDialog, configName, idRank) {
    let dialogConfig = DialogClassConfig[configName];

    let url = "get?id=" + encodeURIComponent(idRank);
    new XRequest(url)
    .SetResponseType("json")
    .OnSuccess(function(data) {
        if (data.length !== 0) {
            UpdateDialog(idDialog, dialogConfig, {
                id: data[0].id,
                message: `Apakah Anda ingin menghapus peringkat "${data[0].name}"? ( permanen )`
            });
        }
    })
    .Send();
}