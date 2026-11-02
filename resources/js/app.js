import "./bootstrap";
import * as bootstrap from "bootstrap";
import { initReveal } from "./reveal";
import { initProducts } from "./product";
import { initHotspots } from "./hotspot";
import { initGallery } from "./gallery";
import { initNav } from "./nav";
import { initCatalogSearch } from "./catalog";
import { initCatalogSlider } from "./catalog-slider";

window.bootstrap = bootstrap;

initReveal();
initProducts();
initHotspots();
initGallery();
initNav();
initCatalogSearch();
initCatalogSlider();
