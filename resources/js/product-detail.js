document.addEventListener("DOMContentLoaded", () => {
    const stepper = document.querySelector(".agr-pdp-stepper");
    const qtyInput = document.getElementById("pdp-qty-input");
    const buyNowInput = document.getElementById("pdp-buy-now-input");
    const orderForm = document.getElementById("pdp-order-form");
    const minusBtn = document.getElementById("pdp-qty-minus");
    const plusBtn = document.getElementById("pdp-qty-plus");
    const qtyDisplay = document.getElementById("pdp-qty-display");
    const bottomSummary = document.getElementById("pdp-bottom-summary");
    const bottomPrice = document.getElementById("pdp-bottom-price");
    const addCartBtn = document.getElementById("pdp-add-cart-btn");
    const buyNowBtn = document.getElementById("pdp-buy-now-btn");

    if (
        !stepper ||
        !qtyInput ||
        !minusBtn ||
        !plusBtn ||
        !qtyDisplay ||
        !bottomSummary ||
        !bottomPrice
    ) {
        return;
    }

    const minQty = Number.parseInt(stepper.dataset.min || "1", 10);
    const maxQty = Number.parseInt(stepper.dataset.max || "99", 10);
    const unit = stepper.dataset.unit || "kg";
    const unitPrice = Number.parseFloat(stepper.dataset.price || "0");

    const renderQty = (qty) => {
        const clamped = Math.max(minQty, Math.min(maxQty, qty));
        qtyInput.value = String(clamped);
        qtyDisplay.textContent = `${clamped} ${unit}`;
        bottomSummary.textContent = `Total · ${clamped} ${unit}`;
        bottomPrice.textContent = `Rp${(clamped * unitPrice).toLocaleString("id-ID")}`;
        minusBtn.disabled = clamped <= minQty;
        plusBtn.disabled = clamped >= maxQty;
    };

    minusBtn.addEventListener("click", () => {
        renderQty(Number.parseInt(qtyInput.value || "1", 10) - 1);
    });

    plusBtn.addEventListener("click", () => {
        renderQty(Number.parseInt(qtyInput.value || "1", 10) + 1);
    });

    if (addCartBtn && buyNowInput && orderForm) {
        addCartBtn.addEventListener("click", () => {
            buyNowInput.value = "0";
            orderForm.submit();
        });
    }

    if (buyNowBtn && buyNowInput) {
        buyNowBtn.addEventListener("click", () => {
            buyNowInput.value = "1";
        });
    }

    renderQty(minQty);
});
