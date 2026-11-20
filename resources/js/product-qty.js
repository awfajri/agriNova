document.addEventListener("DOMContentLoaded", () => {
    const qtyInput = document.getElementById("product-quantity");
    if (!qtyInput) return;

    const min = Number.parseInt(qtyInput.min || "1", 10);
    const max = Number.parseInt(qtyInput.max || "999", 10);
    const unitPrice = Number.parseFloat(qtyInput.dataset.unitPrice || "0");
    const unitLabel = qtyInput.dataset.unitLabel || "kg";

    const stickyQtyEl = document.getElementById("detail-sticky-qty");
    const stickyTotalEl = document.getElementById("detail-sticky-total");

    const syncTotal = () => {
        let current = Number.parseInt(qtyInput.value || String(min), 10);
        if (Number.isNaN(current) || current < min) current = min;
        if (current > max) current = max;
        qtyInput.value = String(current);

        if (stickyQtyEl) {
            stickyQtyEl.textContent = `Total · ${current} ${unitLabel}`;
        }
        if (stickyTotalEl) {
            const total = current * unitPrice;
            stickyTotalEl.textContent = `Rp${total.toLocaleString("id-ID")}`;
        }
    };

    document.querySelectorAll("[data-qty-step]").forEach((btn) => {
        btn.addEventListener("click", () => {
            const step = Number.parseInt(btn.dataset.qtyStep || "0", 10);
            const current = Number.parseInt(qtyInput.value || String(min), 10);
            qtyInput.value = String(current + step);
            syncTotal();
        });
    });

    qtyInput.addEventListener("change", syncTotal);
});
