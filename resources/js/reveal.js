export function initReveal() {
    const reduce = window.matchMedia(
        "(prefers-reduced-motion: reduce)",
    ).matches;

    // ---- scroll text: kata muncul saat section masuk layar ----
    const texts = document.querySelectorAll("[data-scroll-text]");
    const textObserver = new IntersectionObserver(
        (entries, obs) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add("is-visible");
                    obs.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.4 },
    );
    texts.forEach((el) => {
        if (reduce) el.classList.add("is-visible");
        else textObserver.observe(el);
    });

    // ---- animated counter ----
    function runCounter(el) {
        const target = Number(el.dataset.target);
        const duration = 1400;
        const start = performance.now();

        function tick(now) {
            const t = Math.min((now - start) / duration, 1);
            const eased = 1 - Math.pow(1 - t, 3);
            el.textContent = Math.round(target * eased);
            if (t < 1) requestAnimationFrame(tick);
        }
        requestAnimationFrame(tick);
    }

    const counterObserver = new IntersectionObserver(
        (entries, obs) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    runCounter(entry.target);
                    obs.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.6 },
    );

    document.querySelectorAll(".counter").forEach((el) => {
        if (reduce) return; // angka akhir sudah tertulis di HTML
        el.textContent = "0";
        counterObserver.observe(el);
    });
}
