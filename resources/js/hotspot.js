import { Modal } from "bootstrap";

export function initHotspots() {
    const modalEl = document.getElementById("facilityModal");
    if (!modalEl) return;

    const modal = Modal.getOrCreateInstance(modalEl);

    // data fasilitas diambil dari titik di atas foto
    const items = [
        ...document.querySelectorAll(".hotspot-stage [data-hotspot]"),
    ].map((el) => ({
        title: el.dataset.title,
        desc: el.dataset.desc,
    }));
    if (!items.length) return;

    const titleEl = document.getElementById("facilityTitle");
    const descEl = document.getElementById("facilityDesc");
    const countEl = document.getElementById("facilityCount");
    let current = 0;

    function render(index) {
        current = (index + items.length) % items.length;
        titleEl.textContent = items[current].title;
        descEl.textContent = items[current].desc;
        countEl.textContent = `${current + 1} / ${items.length}`;
    }

    // titik di foto dan chip sama-sama membuka modal
    document.querySelectorAll("[data-hotspot]").forEach((el) => {
        el.addEventListener("click", () => {
            render(Number(el.dataset.index));
            modal.show();
        });
    });

    document
        .getElementById("facilityPrev")
        .addEventListener("click", () => render(current - 1));
    document
        .getElementById("facilityNext")
        .addEventListener("click", () => render(current + 1));

    modalEl.addEventListener("keydown", (e) => {
        if (e.key === "ArrowLeft") render(current - 1);
        if (e.key === "ArrowRight") render(current + 1);
    });
}
