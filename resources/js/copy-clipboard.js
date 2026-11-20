document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll("[data-copy-target]").forEach((button) => {
        button.addEventListener("click", async () => {
            const textToCopy = button.dataset.copyTarget;
            if (!textToCopy) return;

            try {
                await navigator.clipboard.writeText(textToCopy);
                const label = button.querySelector("span");
                if (label) {
                    const original = label.textContent;
                    label.textContent = "Tersalin!";
                    setTimeout(() => {
                        label.textContent = original;
                    }, 1800);
                }
            } catch (_) {
                // Fallback jika clipboard API diblokir
            }
        });
    });
});
