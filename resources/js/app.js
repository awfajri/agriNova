import "./bootstrap";
import * as bootstrap from "bootstrap";
import { initReveal } from "./reveal";
import { initProducts } from "./product";
import { initHotspots } from "./hotspot";
import { initGallery } from "./gallery";

window.bootstrap = bootstrap;

initReveal();
initProducts();
initHotspots();
initGallery();
