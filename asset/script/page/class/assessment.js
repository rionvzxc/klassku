var MarkedDates = new Set();
var AgendaRequestId = 0;
var AgendaRequestMonth = "";

function InitAssessment() {
    document.querySelectorAll("[xcheck]").forEach(el => {
        el.addEventListener("click", function() {
            if (this.getAttribute("editable") !== "true") return;

            const value = this.dataset.value === "1" ? 0 : 1;

            this.dataset.value = value;
            this.setAttribute("xcheck", value === 1 ? "s1" : "s0");
            this.textContent = value === 1 ? "✓" : "-";
        });
    });

    new SimpleDatePick(document.querySelector("#assessment-date"), {
        onOpen: function(dObj, dStr, fp) {
            GetAgendaDate(fp);
        },
        onMonthChange: function(dObj, dStr, fp) {
            GetAgendaDate(fp);
        },
        onYearChange: function(dObj, dStr, fp) {
            GetAgendaDate(fp);
        },
        onDayCreate: function(dObj, dStr, fp, dayElem) {
            let date = fp.formatDate(dayElem.dateObj);

            if (MarkedDates.has(date)) {
                const mark = document.createElement("span");
                mark.className = "event-mark";
                mark.setAttribute("aria-hidden", "true");
                dayElem.append(mark);
                dayElem.setAttribute("aria-label", dayElem.getAttribute("aria-label") + ", ada agenda");
            }
        }
    });
}
function GetAgendaDate(fp) {
    let vMonth = fp.currentYear + "-" + String(fp.currentMonth + 1).padStart(2, "0");

    if (AgendaRequestMonth === vMonth) return;

    const requestId = ++AgendaRequestId;
    AgendaRequestMonth = vMonth;

    new XRequest("get_date?month=" + vMonth)
        .SetMethod("GET")
        .SetResponseType("json")
        .OnSuccess(function(data) {
            if (requestId !== AgendaRequestId) return;

            AgendaRequestMonth = "";

            if (data?.success !== true) return;

            MarkedDates = new Set(data.dates);
            fp.redraw();
        })
        .OnError(() => {
            if (requestId === AgendaRequestId) AgendaRequestMonth = "";
        })
        .Send();
}
function AssessmentEditor(idStudent) {
    let eTblRows = [];

    if (idStudent !== null) {
        eTblRows = document.querySelectorAll(`tr[data-id-student='${idStudent}']`);
    } else {
        eTblRows = document.querySelectorAll(`tr[data-id-student]`);
    }
    eTblRows.forEach(eTblRow => {
        eTblRow.querySelectorAll("[xinput]").forEach(eInput => {
            eInput.setAttribute("contenteditable", "true");
        });
        eTblRow.querySelectorAll("[xcheck]").forEach(eInput => {
            eInput.setAttribute("editable", "true");
        });
        eTblRow.setAttribute("edited", "");
    });
    if (eTblRows.length !== 0) {
        document.querySelector(".assessment").setAttribute("edited", "");
    }
}
function AssessmentUpdate(idAgenda, idStudent) {
    let assessmentData = AssessmentGetRow(idStudent);
    assessmentData.id_agenda = idAgenda;
    const errorMessage = document.querySelector("#assessment-save-error");
    errorMessage.hidden = true;

    new XRequest("update")
        .SetMethod("POST")
        .SetHeader("Content-Type", "application/json")
        .SetResponseType("json")
        .SetBody(JSON.stringify(assessmentData))
        .OnSuccess(data => {
            if (data?.success === true) {
                window.location.reload();
            } else {
                errorMessage.hidden = false;
            }
        })
        .OnError(() => {
            errorMessage.hidden = false;
        })
        .Send();
}
function AssessmentGetRow(idStudent) {
    let eTblRows = [];
    let result = {
        points: []
    };
    if (idStudent !== null) {
        eTblRows = document.querySelectorAll(`tr[edited][data-id-student='${idStudent}']`);
    } else {
        eTblRows = document.querySelectorAll(`tr[edited][data-id-student]`);
    }
    eTblRows.forEach(eTblRow => {
        const data = {
            attendance: [],
            assignment: [],
            participation: []
        };
        if (eTblRow !== null) {
            data.id_student = eTblRow.dataset.idStudent;
            eTblRow.querySelectorAll("[data-name][data-index]").forEach(el => {
                const name = el.dataset.name;
                const index = parseInt(el.dataset.index);
                let value = 0;

                if (el.hasAttribute("xinput")) {
                    value = parseInt(el.textContent);
                } else {
                    value = parseInt(el.dataset.value);
                }
                data[name][index] = value;
            });
        }
        result.points.push(data);
    });
    return result;
}
InitAssessment();
