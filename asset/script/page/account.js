function DialogAccountPasswordForm(idDialog) {
    UpdateDialog(idDialog, {
        dialog: { title: "Ubah Password" },
        form: { method: "POST", action: "update-password" }
    }, {})
}