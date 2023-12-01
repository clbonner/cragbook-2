import { createTemplate, loadTemplateView, getResponseText } from './helpers.js';
import { Breadcrumbs } from './breadcrumb.js';
import { viewCrags, viewCrag } from './crag.js';
import { viewGuides, viewGuide } from './guide.js';
import { viewAreas, viewArea } from './area.js';

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
    document.getElementById("home").addEventListener("click", () => {
        history.pushState({page: "home"}, "", "/");
        viewHome();
    });
    document.getElementById("guides").addEventListener("click", () => {
        history.pushState({page: "guides"}, "", "/guides");
        viewGuides();
    });
    document.getElementById("areas").addEventListener("click", () => {
        history.pushState({page: "areas"}, "", "/areas");
        viewAreas();
    });
    document.getElementById("crags").addEventListener("click", () => {
        history.pushState({page: "crags"}, "", "/crags");
        viewCrags();
    });
    document.getElementById("search").addEventListener("keyup", searchAll);

    // event listener for browser history popstate
    addEventListener("popstate", appRouter);
    appRouter();
}

// directs application to correct page on history state or path name
function appRouter() {
    let state = history.state;

    if (state === null) {
        let path = window.location.pathname.split("/");
        if (path.length == 2) state = {page: path[1]};
        else if (path.length == 3) state = {page: path[1], id: path[2]};
        else state = {page: "home"};
    }
    
    if (state.page == "guides") viewGuides();
    else if (state.page == "guide") viewGuide(state.id);
    else if (state.page == "areas") viewAreas();
    else if (state.page == "area") viewArea(state.id);
    else if (state.page == "crags") viewCrags();
    else if (state.page == "crag") viewCrag(state.id);
    else if (state.page == "home") viewHome();
}

export { viewHome, searchAll, initApp };