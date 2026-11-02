export function initCatalogSearch() {
    const forms = document.querySelectorAll(".catalog-search, .nav-search");

    forms.forEach((form) => {
        const input = form.querySelector("input[type='search']");
        const clearButton = form.querySelector(".nav-search-clear");

        if (!input) return;

        const updateSearchState = () => {
            form.classList.toggle("has-query", input.value.trim().length > 0);
        };

        clearButton?.addEventListener("click", () => {
            input.value = "";
            updateSearchState();
            form.submit();
        });

        input.addEventListener("input", updateSearchState);
        updateSearchState();
    });
}
