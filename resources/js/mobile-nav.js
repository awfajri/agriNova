function initAgrinovaMobileNav() {
    const drawer = document.getElementById("agrMobileDrawer");
    const openBtn = document.getElementById("agrDrawerOpenBtn");
    const closeBtn = document.getElementById("agrDrawerCloseBtn");

    const setDrawerState = (open) => {
        if (!drawer) return;
        if (open) {
            drawer.removeAttribute("hidden");
            drawer.style.display = "block";
        } else {
            drawer.setAttribute("hidden", "");
            drawer.style.display = "none";
        }
        drawer.setAttribute("aria-hidden", String(!open));
        if (openBtn) openBtn.setAttribute("aria-expanded", String(open));
        document.body.style.overflow = open ? "hidden" : "";
    };

    // Event delegation: tangkap klik meski user menekan ikon SVG di dalam tombol
    document.addEventListener("click", (event) => {
        const clickedOpen = event.target.closest("#agrDrawerOpenBtn");
        if (clickedOpen) {
            event.preventDefault();
            setDrawerState(true);
            return;
        }

        const clickedClose = event.target.closest("#agrDrawerCloseBtn");
        if (clickedClose) {
            event.preventDefault();
            setDrawerState(false);
            return;
        }

        // Fallback tombol Filter (#filterModal) jika Bootstrap data-bs-toggle tidak aktif
        const clickedFilter = event.target.closest(".agr-mob-filter-inside");
        if (clickedFilter) {
            const modalEl = document.getElementById("filterModal");
            if (!modalEl) return;

            if (window.bootstrap && window.bootstrap.Modal) {
                event.preventDefault();
                window.bootstrap.Modal.getOrCreateInstance(modalEl).show();
            }
        }
    });

    // Tutup drawer dengan tombol Escape
    document.addEventListener("keydown", (event) => {
        if (
            event.key === "Escape" &&
            drawer &&
            !drawer.hasAttribute("hidden")
        ) {
            setDrawerState(false);
        }
    });

    // Live Pre-Order Countdown di Hero & Multi-Banner Katalog
    const countdownElements = document.querySelectorAll(".catalog-preorder-countdown, #catalog-preorder-countdown");
    if (countdownElements.length > 0) {
        countdownElements.forEach((el) => {
            const deadline = Number.parseInt(el.dataset.deadline || "0", 10);
            if (deadline > 0) {
                const updateHeroTimer = () => {
                    const diff = deadline - Date.now();
                    if (diff <= 0) {
                        el.textContent = "Periode pre-order sedang berlangsung";
                        return;
                    }
                    const days = String(
                        Math.floor(diff / (1000 * 60 * 60 * 24)),
                    ).padStart(2, "0");
                    const hours = String(
                        Math.floor((diff / (1000 * 60 * 60)) % 24),
                    ).padStart(2, "0");
                    const mins = String(
                        Math.floor((diff / (1000 * 60)) % 60),
                    ).padStart(2, "0");
                    el.textContent = `Tutup dalam ${days} hari : ${hours} jam : ${mins} mnt`;
                };

                updateHeroTimer();
                setInterval(updateHeroTimer, 60000);
            }
        });
    }
}

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initAgrinovaMobileNav);
} else {
    initAgrinovaMobileNav();
}
