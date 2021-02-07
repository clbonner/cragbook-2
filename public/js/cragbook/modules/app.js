import { createTemplate, loadTemplateView, getResponseText } from './helpers.js';
import { Breadcrumbs } from './breadcrumb.js';
import { viewCrags } from './crag.js';
import { viewGuides } from './guide.js';
import { viewAreas } from './area.js';

// global search function
function searchAll() {
    let text = document.getElementById("search").value;
    console.log(text);
}

// shows homepage
function viewHome() {
    let template;

    fetch("/api/template.php?id=home").then(( response ) => {
            return getResponseText(response);
        }).then(( html ) => {
            template = createTemplate(html);
            loadTemplateView(template);
        });
}

function initApp() {
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
}

export { viewHome, searchAll, initApp };