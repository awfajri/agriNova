document.addEventListener("DOMContentLoaded", () => {
    const timerEl = document.getElementById("payment-countdown");
    if (!timerEl) return;

    const expiresAt = Number.parseInt(timerEl.dataset.expiresAt || "0", 10);
    if (!expiresAt) return;

    const updateCountdown = () => {
        const diff = expiresAt - Date.now();

        if (diff <= 0) {
            timerEl.textContent = "00:00";
            timerEl.classList.add("is-expired");
            clearInterval(intervalId);
            return;
        }

        const totalSeconds = Math.floor(diff / 1000);
        const minutes = String(Math.floor(totalSeconds / 60)).padStart(2, "0");
        const seconds = String(totalSeconds % 60).padStart(2, "0");
        timerEl.textContent = `${minutes}:${seconds}`;
    };

    updateCountdown();
    const intervalId = setInterval(updateCountdown, 1000);
});
