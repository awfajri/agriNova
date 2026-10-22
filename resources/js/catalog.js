export function initCatalogSearch() {
    const form = document.querySelector(".catalog-search");
    const input = form?.querySelector("input[type='search']");

    if (!form || !input) return;

    const updateSearchState = () => {
        form.classList.toggle("has-query", input.value.trim().length > 0);
    };

    input.addEventListener("input", updateSearchState);
    updateSearchState();
}
