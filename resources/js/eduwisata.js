document.addEventListener("DOMContentLoaded", () => {
    const pesertaInput = document.getElementById("jumlah_peserta");
    const totalDisplay = document.getElementById("edu-total-display");
    if (!pesertaInput || !totalDisplay) return;

    const unitPrice = Number.parseFloat(pesertaInput.dataset.unitPrice || "0");

    const updateEduTotal = () => {
        const count = Math.max(
            1,
            Number.parseInt(pesertaInput.value || "1", 10) || 1,
        );
        const total = count * unitPrice;
        totalDisplay.textContent = `Rp${total.toLocaleString("id-ID")}`;
    };

    pesertaInput.addEventListener("input", updateEduTotal);
    updateEduTotal();
});
