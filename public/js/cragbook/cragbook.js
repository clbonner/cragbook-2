import { Breadcrumbs } from './modules/breadcrumb.js';
import { viewCrags } from './modules/crag.js';
import { viewGuides } from './modules/guide.js';
import { viewAreas } from './modules/area.js';
import { viewHome, searchAll } from './modules/app.js';

// global namespace for app
globalThis.cragbook = {
    trail : new Breadcrumbs()
};

// set functions for main menu navigation
document.getElementById("home").addEventListener("click", viewHome);
document.getElementById("guides").addEventListener("click", viewGuides);
document.getElementById("areas").addEventListener("click", viewAreas);
document.getElementById("crags").addEventListener("click", viewCrags);
document.getElementById("search").addEventListener("keyup", searchAll);

viewHome();