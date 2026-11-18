class SimpleDatePick {
    constructor(input, hooks = {}) {
        this.input = input;
        this.hooks = hooks;
        this.monthNames = [
            "Januari", "Februari", "Maret", "April", "Mei", "Juni",
            "Juli", "Agustus", "September", "Oktober", "November", "Desember"
        ];
        const wrapper = document.createElement("div");
        wrapper.className = "simple-date-pick";
        wrapper.innerHTML = `
            <button type="button" class="simple-date-pick-input" aria-expanded="false"></button>
            <div class="simple-date-pick-calendar" hidden>
                <div class="simple-date-pick-header">
                    <button type="button" aria-label="Bulan sebelumnya">&#8249;</button>
                    <select aria-label="Bulan"></select>
                    <input type="number" aria-label="Tahun" min="1000" max="9999">
                    <button type="button" aria-label="Bulan berikutnya">&#8250;</button>
                </div>
                <div class="simple-date-pick-days"></div>
            </div>`;
        input.after(wrapper);
        input.type = "hidden";
        this.trigger = wrapper.querySelector(".simple-date-pick-input");
        this.calendar = wrapper.querySelector(".simple-date-pick-calendar");
        this.calendar.id = input.id + "-calendar";
        this.trigger.setAttribute("aria-controls", this.calendar.id);
        this.month = this.calendar.querySelector("select");
        this.year = this.calendar.querySelector("input");
        this.days = this.calendar.querySelector(".simple-date-pick-days");
        [this.previous, this.next] = this.calendar.querySelectorAll("button");
        this.monthNames.forEach((name, index) => this.month.add(new Option(name, index)));

        this.trigger.addEventListener("click", () => this.calendar.hidden ? this.open() : this.close());
        this.previous.addEventListener("click", () => this.move(this.currentYear, this.currentMonth - 1));
        this.next.addEventListener("click", () => this.move(this.currentYear, this.currentMonth + 1));
        this.month.addEventListener("change", () => this.move(this.currentYear, Number(this.month.value)));
        this.year.addEventListener("change", () => this.move(Number(this.year.value), this.currentMonth));
        this.year.addEventListener("keydown", event => {
            if (event.key === "Enter") {
                event.preventDefault();
                this.move(Number(this.year.value), this.currentMonth);
            }
        });
        document.addEventListener("click", event => {
            if (!wrapper.contains(event.target)) this.close();
        });
        window.addEventListener("resize", () => this.positionCalendar());
        window.addEventListener("scroll", () => this.positionCalendar(), true);
        wrapper.addEventListener("keydown", event => {
            if (event.key === "Escape" && !this.calendar.hidden) {
                event.preventDefault();
                this.close(true);
            }
        });
        this.setDate(input.value);
    }
    formatDate(date) {
        const pad = value => String(value).padStart(2, "0");
        return date.getFullYear() + "-" + pad(date.getMonth() + 1) + "-" + pad(date.getDate());
    }
    displayDate(date) {
        return date.getDate() + " " + this.monthNames[date.getMonth()] + " " + date.getFullYear();
    }
    setDate(value) {
        const date = /^\d{4}-\d{2}-\d{2}$/.test(value) ? new Date(value + "T00:00:00") : null;
        this.selected = date && this.formatDate(date) === value ? date : null;
        const view = this.selected ?? new Date();
        this.currentYear = view.getFullYear();
        this.currentMonth = view.getMonth();
        this.input.value = this.selected ? this.formatDate(this.selected) : "";
        this.trigger.textContent = this.selected ? this.displayDate(this.selected) : "Pilih tanggal";

        if (!this.calendar.hidden) this.redraw();
    }
    open() {
        this.calendar.hidden = false;
        this.trigger.setAttribute("aria-expanded", "true");
        this.redraw();
        this.emit("onOpen");
    }
    close(restoreFocus = false) {
        if (this.calendar.hidden) return;

        this.calendar.hidden = true;
        this.trigger.setAttribute("aria-expanded", "false");

        if (restoreFocus) this.trigger.focus();
    }
    move(year, month) {
        const target = new Date(year, month, 1);
        const nextYear = target.getFullYear();
        const nextMonth = target.getMonth();

        if (!Number.isInteger(year) || year < 1000 || year > 9999 || nextYear < 1000 || nextYear > 9999) {
            this.year.value = this.currentYear;
            return;
        }
        const changedYear = nextYear !== this.currentYear;
        const changedMonth = nextMonth !== this.currentMonth;
        this.currentYear = nextYear;
        this.currentMonth = nextMonth;

        if (!changedYear && !changedMonth) return;

        this.redraw();

        if (changedYear) this.emit("onYearChange");
        if (changedMonth) this.emit("onMonthChange");
    }
    emit(hook, day) {
        this.hooks[hook]?.(this.selected ? [this.selected] : [], this.input.value, this, day);
    }
    positionCalendar() {
        if (this.calendar.hidden) return;

        const anchor = this.trigger.getBoundingClientRect();
        const { offsetWidth, offsetHeight } = this.calendar;
        const width = document.documentElement.clientWidth;
        const height = document.documentElement.clientHeight;
        const left = Math.max(8, Math.min(anchor.left, width - offsetWidth - 8));
        const below = anchor.bottom + 5;
        const above = anchor.top - offsetHeight - 5;
        const top = below + offsetHeight > height - 8 && above >= 8 ? above : below;
        this.calendar.style.left = left + "px";
        this.calendar.style.top = Math.max(8, Math.min(top, height - offsetHeight - 8)) + "px";
    }
    redraw() {
        const focusedDay = this.days.contains(document.activeElement) ? document.activeElement.dataset.date : "";
        this.month.value = this.currentMonth;
        this.year.value = this.currentYear;
        this.previous.disabled = this.currentYear === 1000 && this.currentMonth === 0;
        this.next.disabled = this.currentYear === 9999 && this.currentMonth === 11;
        this.days.replaceChildren();
        const today = this.formatDate(new Date());
        const first = new Date(this.currentYear, this.currentMonth, 1);
        const last = new Date(this.currentYear, this.currentMonth + 1, 0).getDate();
        const weekdays = ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"];
        const firstColumn = first.getDay();

        weekdays.forEach(name => {
            const label = document.createElement("span");
            label.textContent = name;
            this.days.append(label);
        });
        for (let column = 0; column < firstColumn; column++) {
            this.days.append(document.createElement("span"));
        }
        for (let number = 1; number <= last; number++) {
            const day = document.createElement("button");
            const date = new Date(this.currentYear, this.currentMonth, number);
            const value = this.formatDate(date);
            day.type = "button";
            day.textContent = number;
            day.dataset.date = value;
            day.dateObj = date;
            day.setAttribute("aria-label", this.displayDate(date));
            day.setAttribute("aria-pressed", String(value === this.input.value));

            if (value === today) day.setAttribute("aria-current", "date");

            day.addEventListener("click", () => {
                this.close(true);
                this.setDate(value);
                this.input.dispatchEvent(new Event("change", { bubbles: true }));
            });
            this.emit("onDayCreate", day);
            this.days.append(day);

            if (value === focusedDay) day.focus({ preventScroll: true });
        }
        this.positionCalendar();
    }
}
