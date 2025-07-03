function parseTimeToDate(timeStr) {
    const now = new Date();
    const [time, modifier] = timeStr.trim().split(" ");
    let [hours, minutes] = time.split(":").map(Number);

    if (modifier === "PM" && hours !== 12) hours += 12;
    if (modifier === "AM" && hours === 12) hours = 0;

    const date = new Date(now.getFullYear(), now.getMonth(), now.getDate(), hours, minutes);
    return date;
}

function markPastTimes() {
    const now = new Date();
    document.querySelectorAll(".schedule-body div").forEach(div => {
        const time = div.textContent.trim();
        const date = parseTimeToDate(time);
        div.classList.toggle("past", date < now);
    });
}


function getUpcomingTimes(body) {
    const now = new Date();
    return Array.from(body.querySelectorAll("div"))
        .map(div => ({ div, date: parseTimeToDate(div.textContent.trim()) }))
        .filter(obj => obj.date >= now)
        .sort((a, b) => a.date - b.date);
}

function updateSchedule(showAll) {
    document.querySelectorAll(".schedule-body").forEach(body => {
    const allDivs = Array.from(body.querySelectorAll("div"));
    const upcoming = getUpcomingTimes(body);
    const noTripMsg = body.querySelector(".no-trip-message");

    allDivs.forEach(div => div.style.display = "none");
    if (noTripMsg) noTripMsg.style.display = "none";

    if (showAll) {
    allDivs.forEach(div => div.style.display = "block");
    } else {
    if (upcoming.length === 0) {
        if (noTripMsg) noTripMsg.style.display = "block";
    } else {
        upcoming.slice(0, 4).forEach(obj => {
        obj.div.style.display = "block";
        });
    }
    }
});
}


const toggle = document.querySelector('#showAllToggle');

if (toggle) {
    toggle.addEventListener("change", () => {
    updateSchedule(toggle.checked);
    });

    window.addEventListener("load", () => {
    updateSchedule(toggle.checked);
    });
}


function startCountdownFor(body, targetSpan) {
    const upcoming = getUpcomingTimes(body);
    const display = document.getElementById(targetSpan);
    

    if (!upcoming.length) {
        display.textContent = "-";
        return;
    }

    const next = upcoming[0].date;

    function update() {
        const now = new Date();
        const diff = next - now;

        if (diff <= 0) {
        display.textContent = "Departed";
        clearInterval(timer);
        return;
        }

        const m = String(Math.floor(diff / (1000 * 60))).padStart(2, "0");
        const s = String(Math.floor((diff % (1000 * 60)) / 1000)).padStart(2, "0");

        display.textContent = `${m}:${s}`;
    }

    update();
    const timer = setInterval(update, 1000);
}


document.addEventListener("DOMContentLoaded", () => {
    const toggle = document.getElementById("showAllToggle");
    updateSchedule(toggle.checked);
    markPastTimes();
    startCountdownFor(
        document.querySelector(".LRTtoUMT-schedule-container .schedule-body"),
        "countdown-lrt"
        );
    startCountdownFor(
        document.querySelector(".UMTtoLRT-schedule-container .schedule-body"),
        "countdown-umt"
        );

    toggle.addEventListener("change", () => {
        updateSchedule(toggle.checked);
        });

    setInterval(markPastTimes, 60000);
    });