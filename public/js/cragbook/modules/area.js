import { createBreadcrumb } from './breadcrumb.js';
import { createMap, defaultCenter } from './maps.js';
import { ListPagination, createList, createPaginationControl } from './pagination.js';
import { viewCrag } from './crag.js';
import { createTemplate, loadTemplateView, getResponseText } from './helpers.js';

// shows all areas in the database
function viewAreas() {
    let pagination, areas, template;
    let id = "areaid";
    let destination = viewArea;
    let element = "list";

    // get areas json data
    fetch("/api/request.php?request=areas").then( (response) => {
        return getResponseText(response).then(( json ) => {
            areas = JSON.parse(json);
            
            // get template
            fetch("/api/template.php?id=areas").then((response) => {
                return getResponseText(response);
            }).then( (html) => {
                template = createTemplate(html);
                pagination = new ListPagination(areas, 5);
                createPaginationControl(pagination, id, destination, element, template);
                createList(pagination.getPage(0), id, destination, element, template);
                createMap(id, defaultCenter, areas, destination, template);
                loadTemplateView(template);
                cragbook.trail.addCrumb("Areas", viewAreas);
                createBreadcrumb();
            });
        })
        
    })
    
}

// shows an individual area from the database
function viewArea(id) {
    let crags, area, latlng, center, template;

    // get area information
    fetch("/api/request.php?request=area&id=" + id).then(( response ) => {
        return getResponseText(response).then( (json) => {
            area = JSON.parse(json);
            crags = area.crags;

            // get area template
            fetch("/api/template.php?id=area").then( (response) => {
                return getResponseText(response);
            }).then( (html) => {
                template = createTemplate(html);

                template.getElementById("name").innerHTML = area.name;
                template.getElementById("description").innerHTML = area.description;

                latlng = area.location.split(",");
                center = new google.maps.LatLng(latlng[0], latlng[1]);
                createMap("areaid", center, crags, viewArea, template);
                createList(crags, "cragid", viewCrag, "list", template);
                loadTemplateView(template);
                cragbook.trail.addCrumb(area.name, () => (viewArea(area.areaid)));
                createBreadcrumb();
            });
        })
    })
}

export { viewAreas, viewArea };