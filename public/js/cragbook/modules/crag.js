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
    fetch("/api/json.php?file=crags").then(( response ) => {
        return getResponseText(response);
    }).then(( json ) => {
        crags = JSON.parse(json);

        // get page template
        fetch("/api/template.php?id=crags").then(( response ) => {
            return getResponseText(response);
        }).then(( html ) => {
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
    fetch("/api/json.php?file=crag").then(( response ) => {
        return getResponseText(response);
    }).then(( json ) => {
        crag = JSON.parse(json);

        // get routes json data
        fetch("/api/json.php?file=routes").then(( response ) => {
            return getResponseText(response);
        }).then(( json ) => {
            routes = new RouteList(JSON.parse(json));

            // get page template
            fetch("/api/template.php?id=crag").then(( response ) => {
                return getResponseText(response);
            }).then(( html ) => {
                latlng = crag[0].location.split(",");
                center = new google.maps.LatLng(latlng[0], latlng[1]);

                template = createTemplate(html);
                createCragInfo(crag, template);
                createGradeSummary(routes, template);
                createMap("cragid", center, crag, viewCrag, template);
                createRouteTable(routes.getAllRoutes(), template);
                setRouteFilter(routes, template);
                setRouteTableHeaderFilters(routes, template);
                loadTemplateView(template);
                cragbook.trail.addCrumb(crag[0].name, () => (viewCrag(crag[0].cragid)));
                createBreadcrumb();
            });
        });
    });
    
}

// inserts data to the crag page template for the info section
function createCragInfo(crag, template) {
    template.getElementById("name").innerHTML = crag[0].name;
    template.getElementById("description").innerHTML = crag[0].description;
    template.getElementById("approach").innerText = crag[0].approach;
    template.getElementById("access").innerText = crag[0].access;
    template.getElementById("policy").innerText = crag[0].policy;
}

export { viewCrags, viewCrag };