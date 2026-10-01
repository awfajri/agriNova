function initTilt() {
    const canHover = window.matchMedia(
        "(hover: hover) and (pointer: fine)",
    ).matches;
    const reduce = window.matchMedia(
        "(prefers-reduced-motion: reduce)",
    ).matches;
    if (!canHover || reduce) return;

    const MAX = 8; // derajat kemiringan maksimal

    document.querySelectorAll("[data-tilt]").forEach((card) => {
        card.addEventListener("pointermove", (e) => {
            const r = card.getBoundingClientRect();
            const x = (e.clientX - r.left) / r.width - 0.5;
            const y = (e.clientY - r.top) / r.height - 0.5;

            card.style.setProperty("--ry", `${x * MAX * 2}deg`);
            card.style.setProperty("--rx", `${-y * MAX * 2}deg`);
            card.classList.add("is-tilting");
        });

        card.addEventListener("pointerleave", () => {
            card.style.setProperty("--rx", "0deg");
            card.style.setProperty("--ry", "0deg");
            card.classList.remove("is-tilting");
        });
    });
}

function initCountdown() {
    document.querySelectorAll("[data-countdown]").forEach((root) => {
        const end = new Date(root.dataset.countdown).getTime();
        if (Number.isNaN(end)) return;

        const els = {
            days: root.querySelector('[data-cd="days"]'),
            hours: root.querySelector('[data-cd="hours"]'),
            minutes: root.querySelector('[data-cd="minutes"]'),
            seconds: root.querySelector('[data-cd="seconds"]'),
        };
        const pad = (n) => String(n).padStart(2, "0");
        let timer;

        function update() {
            const diff = Math.max(0, end - Date.now());
            const s = Math.floor(diff / 1000);

            els.days.textContent = pad(Math.floor(s / 86400));
            els.hours.textContent = pad(Math.floor((s % 86400) / 3600));
            els.minutes.textContent = pad(Math.floor((s % 3600) / 60));
            els.seconds.textContent = pad(s % 60);

            if (diff === 0) clearInterval(timer);
        }

        update();
        timer = setInterval(update, 1000);
    });
}

export function initProducts() {
    initTilt();
    initCountdown();
}
