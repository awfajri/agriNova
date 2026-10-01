export function initStack() {
    const root = document.getElementById("stack");
    if (!root) return;

    const cards = [...root.querySelectorAll(".stack-card")];
    const n = cards.length;
    const half = Math.floor(n / 2);
    let active = 0;

    function render() {
        cards.forEach((card, i) => {
            // jarak melingkar: kartu paling ujung nyambung ke sisi seberang
            const o = ((i - active + n + half) % n) - half;
            const abs = Math.abs(o);

            card.style.setProperty("--o", o);
            card.style.setProperty("--abs", abs);
            card.style.zIndex = 10 - abs;
            card.classList.toggle("is-active", o === 0);
            card.classList.toggle("is-far", abs >= 2);
            card.toggleAttribute("aria-current", o === 0);
        });
    }

    const go = (dir) => {
        active = (active + dir + n) % n;
        render();
    };

    root.querySelector("#stackPrev").addEventListener("click", () => go(-1));
    root.querySelector("#stackNext").addEventListener("click", () => go(1));

    cards.forEach((card, i) => {
        card.addEventListener("click", (e) => {
            if (!card.classList.contains("is-active")) {
                e.preventDefault();
                active = i;
                render();
            } else if (card.getAttribute("href") === "#") {
                e.preventDefault(); // halaman tujuan belum ada
            }
        });
    });

    root.addEventListener("keydown", (e) => {
        if (e.key === "ArrowLeft") go(-1);
        if (e.key === "ArrowRight") go(1);
    });

    render();
}
