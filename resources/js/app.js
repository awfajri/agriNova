import "./bootstrap";
import * as bootstrap from "bootstrap";
import { initReveal } from "./reveal";
import { initProducts } from "./product";

window.bootstrap = bootstrap;

initReveal();
initProducts();
