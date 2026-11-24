export function initCatalogSlider() {
    const slider = document.querySelector("[data-catalog-slider]");
    if (!slider) return;

    const track = slider.querySelector("[data-catalog-slider-track]");
    const slides = [...slider.querySelectorAll("[data-catalog-slide]")];
    const dots = [...slider.querySelectorAll("[data-catalog-dot]")];
    const previous = slider.querySelector("[data-catalog-prev]");
    const next = slider.querySelector("[data-catalog-next]");

    if (!track || slides.length === 0) return;

    if (slides.length < 2) {
        if (previous) previous.style.display = "none";
        if (next) next.style.display = "none";
        dots.forEach((dot) => (dot.style.display = "none"));
        return;
    }

    let index = 0;
    let startX = 0;
    let currentX = 0;
    let isDragging = false;
    let autoPlay = null;

    const setIndex = (nextIndex, animate = true) => {
        if (nextIndex < 0) {
            index = slides.length - 1;
        } else if (nextIndex >= slides.length) {
            index = 0;
        } else {
            index = nextIndex;
        }

        track.style.transition = animate ? "transform 460ms cubic-bezier(.22,.61,.36,1)" : "none";
        track.style.transform = `translate3d(${-index * 100}%, 0, 0)`;

        dots.forEach((dot, dotIndex) => {
            const active = dotIndex === index;
            dot.classList.toggle("is-active", active);
            dot.setAttribute("aria-selected", String(active));
        });
    };

    const stopAutoPlay = () => {
        if (autoPlay) {
            window.clearInterval(autoPlay);
            autoPlay = null;
        }
    };

    const startAutoPlay = () => {
        stopAutoPlay();
        if (window.matchMedia("(prefers-reduced-motion: reduce)").matches) return;
        autoPlay = window.setInterval(() => {
            setIndex(index + 1);
        }, 4500);
    };

    const finishDrag = () => {
        if (!isDragging) return;
        isDragging = false;
        slider.classList.remove("is-dragging");
        const distance = currentX - startX;
        const threshold = slider.offsetWidth * 0.15;

        if (Math.abs(distance) > threshold) {
            setIndex(index + (distance < 0 ? 1 : -1));
        } else {
            setIndex(index);
        }

        startAutoPlay();
    };

    track.addEventListener("pointerdown", (event) => {
        if (event.target.closest("a, button")) return;
        isDragging = true;
        startX = event.clientX;
        currentX = startX;
        track.setPointerCapture(event.pointerId);
        slider.classList.add("is-dragging");
        stopAutoPlay();
    });

    track.addEventListener("pointermove", (event) => {
        if (!isDragging) return;
        currentX = event.clientX;
        const offset = ((currentX - startX) / slider.offsetWidth) * 100;
        track.style.transition = "none";
        track.style.transform = `translate3d(${(-index * 100) + offset}%, 0, 0)`;
    });

    track.addEventListener("pointerup", finishDrag);
    track.addEventListener("pointercancel", finishDrag);
    track.addEventListener("pointerleave", () => {
        if (isDragging) finishDrag();
    });

    previous?.addEventListener("click", (e) => {
        e.preventDefault();
        setIndex(index - 1);
        startAutoPlay();
    });

    next?.addEventListener("click", (e) => {
        e.preventDefault();
        setIndex(index + 1);
        startAutoPlay();
    });

    dots.forEach((dot) => {
        dot.addEventListener("click", (e) => {
            e.preventDefault();
            setIndex(Number(dot.dataset.catalogDot));
            startAutoPlay();
        });
    });

    slider.addEventListener("keydown", (event) => {
        if (event.key === "ArrowLeft") {
            event.preventDefault();
            setIndex(index - 1);
            startAutoPlay();
        }
        if (event.key === "ArrowRight") {
            event.preventDefault();
            setIndex(index + 1);
            startAutoPlay();
        }
    });

    slider.addEventListener("mouseenter", stopAutoPlay);
    slider.addEventListener("mouseleave", startAutoPlay);

    setIndex(0, false);
    startAutoPlay();
}
