import { createBreadcrumb } from './breadcrumb.js';
import { createList } from './pagination.js';
import { viewCrag  } from './crag.js';
import { createTemplate, loadTemplateView, getResponseText } from './helpers.js';

// view all guides
function viewGuides() {
    let guides, template;
    
    // get guides data
    fetch("/api/request.php?request=guides").then(( response ) => {
        return getResponseText(response);
    }).then(( json ) => {
        guides = JSON.parse(json);

        // get page template
        fetch("/api/template.php?id=guides").then(( response ) => {
            return getResponseText(response);
        }).then(( html ) => {
            template = createTemplate(html);
            createGuideList(guides, viewGuide, template);
            loadTemplateView(template);

            cragbook.trail.addCrumb("Guides", viewGuides);
            createBreadcrumb();
        });
    });
}

// view a single guide
function viewGuide(id) {
    let guide, crags, template;

    // get guide data
    fetch("/api/request.php?request=guide&id=" + id).then(( response ) => {
        return getResponseText(response);
    }).then(( json ) => {
        guide = JSON.parse(json);
        crags = guide.crags;

        // fetch guide template
        fetch("/api/template.php?id=guide").then(( response ) => {
                return getResponseText(response);
        }).then(( html ) => {
            template = createTemplate(html);
            createGuide(guide, template);

            createList(crags, "cragid", viewCrag, "list", template);
            loadTemplateView(template);

            cragbook.trail.addCrumb(guide.name, () => (viewGuide(guide.guideid)));
            createBreadcrumb();
        });
    });
}


// takes an array of guides and a function name as dest
function createGuideList(guides, event, template) {
    let guide;
    let guideTemplate = template.getElementById("guidetemplate");

    guides.forEach(item => {
        guide = guideTemplate.content.cloneNode(true);
        guide.getElementById("cover").setAttribute("src", item.cover);
        guide.getElementById("name").innerText = item.name;
        guide.getElementById("subtitle").innerText = item.subtitle;
        guide.getElementById("guide").addEventListener("click", () => (event(item.guideid)));
        template.getElementById("list").appendChild(guide);
    });
}

// creates the guide in the DOM
function createGuide(guide, template) {
    let cover = template.getElementById("cover");
    cover.setAttribute("src", guide.cover);

    //template.getElementById("cover").setAttributeNode(cover);
    template.getElementById("name").innerText = guide.name;
    template.getElementById("subtitle").innerText = guide.subtitle;
    template.getElementById("author").innerText = guide.author;
    template.getElementById("publishdate").innerText = guide.publishdate;
    template.getElementById("introduction").innerText = guide.introduction;
    template.getElementById("historical").innerText = guide.historical;
    template.getElementById("grades").innerText = guide.grades;
    template.getElementById("access").innerText = guide.access;
    template.getElementById("ethics").innerText = guide.ethics;
}

export { viewGuides, viewGuide, createGuideList };