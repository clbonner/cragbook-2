import { createTemplate, loadTemplateView, getResponseText } from './helpers.js';

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

export { viewHome, searchAll };