import { Collapse } from "bootstrap";

export function initNav() {
    const nav = document.querySelector(".nav-pill");
    if (!nav) return;

    // ---- tutup menu lipat (HP) setelah link diklik ----
    const collapseEl = document.getElementById("mainNav");
    nav.querySelectorAll("a").forEach((a) => {
        a.addEventListener("click", () => {
            if (collapseEl && collapseEl.classList.contains("show")) {
                Collapse.getOrCreateInstance(collapseEl).hide();
            }
        });
    });

    // ---- menu aktif mengikuti section yang sedang terlihat ----
    const links = [...nav.querySelectorAll(".nav-link[data-spy]")];
    const map = new Map();
    links.forEach((link) => {
        const section = document.getElementById(link.dataset.spy);
        if (section) map.set(section, link);
    });
    if (!map.size) return; // bukan di halaman Home

    const setActive = (active) =>
        links.forEach((l) => l.classList.toggle("active", l === active));

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) setActive(map.get(entry.target));
            });
        },
        { rootMargin: "-45% 0px -50% 0px" }, // pita tipis di tengah layar
    );
    map.forEach((_, section) => observer.observe(section));
}
