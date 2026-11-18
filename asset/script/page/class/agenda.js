let AgendaDatePicker;
let AgendaDialogRequestId = 0;

var DialogClassConfig = {
    "update": {
        dialog: { title: "Ubah Data Agenda" },
        form: { method: "POST", action: "update" }
    },
    "add": {
        dialog: { title: "Tambah Agenda" },
        form: { method: "POST", action: "add" }
    },
    "delete": {
        dialog: { title: "Hapus Agenda" },
        form: { method: "POST", action: "delete" }
    }
}
function DialogAgendaForm(idDialog, configName, idAgenda) {
    let dialogConfig = DialogClassConfig[configName];
    const requestId = ++AgendaDialogRequestId;
    const picker = AgendaDatePicker ??= new SimpleDatePick(document.querySelector("#date"));
    picker.setDate("");
    UpdateDialog(idDialog, dialogConfig, { id: "", name: "", meeting_count: "" });

    if (idAgenda !== null) {
        let url = "get?id=" + encodeURIComponent(idAgenda);
        new XRequest(url)
        .SetResponseType("json")
        .OnSuccess(function(data) {
            if (requestId === AgendaDialogRequestId && data?.length) {
                picker.setDate(data[0].date);
                UpdateDialog(idDialog, dialogConfig, {
                    id: data[0].id,
                    name: data[0].name,
                    meeting_count: data[0].meeting_count
                })
            }
        })
        .Send();
    }
}
function DialogAgendaConfirm(idDialog, configName, idAgenda) {
    let dialogConfig = DialogClassConfig[configName];

    let url = "get?id=" + encodeURIComponent(idAgenda);
    new XRequest(url)
    .SetResponseType("json")
    .OnSuccess(function(data) {
        if (data.length !== 0) {
            UpdateDialog(idDialog, dialogConfig, {
                id: data[0].id,
                message: `Apakah Anda ingin menghapus agenda "${data[0].name}"? ( permanen )`
            });
        }
    })
    .Send();
}
