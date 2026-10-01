export function initGallery() {
    const cards = [...document.querySelectorAll("[data-gallery-card]")];
    const lb = document.getElementById("lightbox");
    if (!cards.length || !lb) return;

    const panel = lb.querySelector(".lightbox-panel");
    const backdrop = lb.querySelector(".lightbox-backdrop");
    const closeBtn = lb.querySelector(".lightbox-close");
    const media = lb.querySelector(".lightbox-media");
    const mediaPh = media.querySelector("span");
    const body = lb.querySelector(".lightbox-body");
    const tagEl = lb.querySelector("#lbTag");
    const titleEl = lb.querySelector("#lbTitle");
    const descEl = lb.querySelector("#lbDesc");
    const countEl = lb.querySelector("#lbCount");

    const reduce = window.matchMedia(
        "(prefers-reduced-motion: reduce)",
    ).matches;
    const EASE = "cubic-bezier(0.22, 0.8, 0.3, 1)";
    const n = cards.length;
    const fadeEls = [media, body, closeBtn];

    let current = 0;
    let isOpen = false;
    let busy = false;

    const settle = (anims) =>
        Promise.all(anims.map((a) => a.finished.catch(() => {})));

    // transform yang membuat panel tampak seukuran & sepososi kartu
    function flipTransform(from, to) {
        const dx = from.left - to.left;
        const dy = from.top - to.top;
        const sx = from.width / to.width;
        const sy = from.height / to.height;
        return `translate(${dx}px, ${dy}px) scale(${sx}, ${sy})`;
    }

    function fill(i) {
        current = (i + n) % n;
        const c = cards[current];
        const img = c.dataset.img || "";

        tagEl.textContent = c.dataset.cat;
        titleEl.textContent = c.dataset.title;
        descEl.textContent = c.dataset.desc;
        countEl.textContent = `${current + 1} / ${n}`;

        media.className = `lightbox-media gv-${current % 5}`;
        media.style.backgroundImage = img ? `url('${img}')` : "";
        mediaPh.hidden = Boolean(img);
    }

    async function open(i) {
        if (isOpen || busy) return;
        fill(i);
        lb.hidden = false;
        document.body.style.overflow = "hidden";
        isOpen = true;
        closeBtn.focus({ preventScroll: true });
        if (reduce) return;

        busy = true;
        const from = cards[i].getBoundingClientRect();
        const to = panel.getBoundingClientRect();

        const anims = [
            backdrop.animate([{ opacity: 0 }, { opacity: 1 }], {
                duration: 300,
                easing: "ease",
            }),
            panel.animate(
                [{ transform: flipTransform(from, to) }, { transform: "none" }],
                { duration: 450, easing: EASE },
            ),
            ...fadeEls.map((el) =>
                el.animate([{ opacity: 0 }, { opacity: 1 }], {
                    duration: 250,
                    delay: 200,
                    fill: "backwards",
                }),
            ),
        ];
        await settle(anims);
        busy = false;
    }

    async function close() {
        if (!isOpen || busy) return;
        busy = true;

        if (!reduce) {
            const r = cards[current].getBoundingClientRect();
            const to = panel.getBoundingClientRect();
            const visible =
                r.bottom > 0 &&
                r.top < window.innerHeight &&
                r.right > 0 &&
                r.left < window.innerWidth;

            const anims = [
                backdrop.animate([{ opacity: 1 }, { opacity: 0 }], {
                    duration: 300,
                    easing: "ease",
                    fill: "forwards",
                }),
                ...fadeEls.map((el) =>
                    el.animate([{ opacity: 1 }, { opacity: 0 }], {
                        duration: 150,
                        fill: "forwards",
                    }),
                ),
                visible
                    ? panel.animate(
                          [
                              { transform: "none" },
                              { transform: flipTransform(r, to) },
                          ],
                          { duration: 380, easing: EASE, fill: "forwards" },
                      )
                    : panel.animate(
                          [
                              { opacity: 1, transform: "none" },
                              { opacity: 0, transform: "scale(0.96)" },
                          ],
                          { duration: 250, fill: "forwards" },
                      ),
            ];
            await settle(anims);
            lb.hidden = true;
            anims.forEach((a) => a.cancel());
        } else {
            lb.hidden = true;
        }

        document.body.style.overflow = "";
        isOpen = false;
        busy = false;
        cards[current].focus({ preventScroll: true });
    }

    function go(dir) {
        if (!isOpen || busy) return;
        fill(current + dir);
        if (!reduce) {
            [media, body].forEach((el) =>
                el.animate([{ opacity: 0.2 }, { opacity: 1 }], {
                    duration: 250,
                }),
            );
        }
    }

    cards.forEach((card, i) => card.addEventListener("click", () => open(i)));
    backdrop.addEventListener("click", close);
    closeBtn.addEventListener("click", close);
    lb.querySelector("#lbPrev").addEventListener("click", () => go(-1));
    lb.querySelector("#lbNext").addEventListener("click", () => go(1));

    document.addEventListener("keydown", (e) => {
        if (!isOpen) return;

        if (e.key === "Escape") close();
        else if (e.key === "ArrowLeft") go(-1);
        else if (e.key === "ArrowRight") go(1);
        else if (e.key === "Tab") {
            // fokus keyboard tetap berputar di dalam modal
            const f = [...lb.querySelectorAll("button")];
            const first = f[0];
            const last = f[f.length - 1];
            if (e.shiftKey && document.activeElement === first) {
                e.preventDefault();
                last.focus();
            } else if (!e.shiftKey && document.activeElement === last) {
                e.preventDefault();
                first.focus();
            }
        }
    });
}
