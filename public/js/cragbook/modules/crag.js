import { createBreadcrumb } from './breadcrumb.js';
import { createMap, defaultCenter } from './maps.js';
import { ListPagination, createList, createPaginationControl } from './pagination.js';
import { getResponseText, createTemplate, loadTemplateView } from './helpers.js';
import { RouteList, createRouteTable, setRouteFilter, setRouteTableHeaderFilters, createGradeSummary } from './routes.js';

// view all crags
function viewCrags() {
    let crags, pagination, template;
    let id = "cragid";
    let destination = viewCrag;
    let element = "list";

    // get crags json data
    fetch("/api/request.php?request=crags").then((response) => {
        return getResponseText(response);
    }).then((json) => {
        crags = JSON.parse(json);

        // get page template
        fetch("/api/template.php?id=crags").then((response) => {
            return getResponseText(response);
        }).then((html) => {
            template = createTemplate(html);
            pagination = new ListPagination(crags, 5);
            createPaginationControl(pagination, id, destination, element, template);
            createList(pagination.getPage(0), id, destination, element, template);
            createMap(id, defaultCenter, crags, destination, template);
            loadTemplateView(template);
            cragbook.trail.addCrumb("Crags", viewCrags);
            createBreadcrumb();
        });
    });

}

// view a crag
function viewCrag(id) {
    let crag, routes, latlng, center, template;

    // get crag json data
    fetch("/api/request.php?request=crag&id=" + id).then((response) => {
        return getResponseText(response);
    }).then((json) => {
        crag = JSON.parse(json);
        routes = new RouteList(crag.routes);

        // get page template
        fetch("/api/template.php?id=crag").then((response) => {
            return getResponseText(response);
        }).then((html) => {
            latlng = crag.location.split(",");
            center = new google.maps.LatLng(latlng[0], latlng[1]);

            template = createTemplate(html);
            createCragInfo(crag, template);
            createGradeSummary(routes, template);
            createMap("cragid", center, [crag], viewCrag, template);
            createRouteTable(routes.getAllRoutes(), template);
            setRouteFilter(routes, template);
            setRouteTableHeaderFilters(routes, template);
            loadTemplateView(template);
            cragbook.trail.addCrumb(crag.name, () => (viewCrag(crag.cragid)));
            createBreadcrumb();
        });
    });
}

// inserts data to the crag page template for the info section
function createCragInfo(crag, template) {
    template.getElementById("name").innerText = crag.name;
    template.getElementById("description").innerText = crag.description;
    template.getElementById("approach").innerText = crag.approach;
    template.getElementById("access").innerText = crag.access;
    template.getElementById("policy").innerText = crag.policy;
}

export { viewCrags, viewCrag };