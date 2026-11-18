function InitMain() {
    const dialogArea = document.querySelector("[xdialog-area]");
    const dialogActionBtns = document.querySelectorAll("[zdialog]");
    const actionBtns = document.querySelectorAll("[zaction]");

    dialogActionBtns.forEach(button => {
        button.addEventListener("click", function(ev) {
            const value = button.getAttribute("zdialog");
            const actionMatch = value.match(/(?:^|\s)(open|close):([^\s]*)/);

            if (!actionMatch) return;

            const status = actionMatch[1];
            const idDialog = actionMatch[2];
            const dialog = document.querySelector(`[xdialog]#${idDialog}`);

            if (dialog !== null) {
                dialogArea.setAttribute("xdialog-area", status);
                dialog.setAttribute("xdialog", status);
            } else {
                dialogArea.setAttribute("xdialog-area", status);
                document.querySelector(`[xdialog]`).forEach(eDialog => {
                    eDialog.setAttribute("xdialog", status);
                })
            }

            const callback = button.dataset.callback;
            const args = `'${idDialog}',` + (button.dataset.args ?? "");

            if (callback) {
                try {
                    eval(`${callback}(${args})`);
                } catch (err) {
                    console.error("Dialog callback error:", err);
                }
            }
        });
    });
    actionBtns.forEach(button => {
        button.addEventListener("click", function(ev) {
            const callback = button.dataset.callback;
            const args = button.dataset.args ?? "";

            if (callback) {
                try {
                    eval(`${callback}(${args})`);
                } catch (err) {
                    console.error("Action callback error:", err);
                }
            }
        });
    });
}
function UpdateDialog(idDialog, dialogConfig, dialogData) {
    const dialog = document.querySelector(`[xdialog]#${idDialog}`);
    const form = document.querySelector(`[xdialog]#${idDialog} form`);

    if ("dialog" in dialogConfig) {
        dialog.querySelector('[vid="dialog-title"]').textContent = dialogConfig.dialog.title;
    }
    if ("form" in dialogConfig) {
        form.action = dialogConfig.form.action;
        form.method = dialogConfig.form.method;
    }
    Object.entries(dialogData).forEach(([key, value]) => {
        form.querySelectorAll(`input[name="${key}"], label[name="${key}"]`).forEach(element => {
            if (element.tagName === "LABEL") {
                element.textContent = value;
            } else {
                element.value = value;
            }
        });
    });
}
function MonthIdFormat(date) {
    let dateResult = date;
    const timestamp = Date.parse(date);

    if (!Number.isNaN(timestamp)) {
        const r = new Intl.DateTimeFormat("id-ID", {
            month: "long",
            year: "numeric",
            timeZone: "Asia/Jakarta"
        }).format(timestamp);

        if (r !== "") {
            dateResult = r;
        }
    }
    return dateResult;
}

window.onload = InitMain;
