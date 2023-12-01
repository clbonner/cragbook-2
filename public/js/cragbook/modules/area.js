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
    let path = "area";

    // get areas json data
    fetch("/api/request.php?request=area").then( (response) => {
        return getResponseText(response).then(( json ) => {
            areas = JSON.parse(json);
            
            // get template
            fetch("/api/template.php?id=areas").then((response) => {
                return getResponseText(response);
            }).then( (html) => {
                template = createTemplate(html);
                pagination = new ListPagination(areas, 5);
                createPaginationControl(pagination, id, destination, element, path, template);
                createList(pagination.getPage(0), id, destination, element, path, template);
                createMap(id, defaultCenter, areas, destination, path, template);
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
    let path = "crag";

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

                template.getElementById("name").innerText = area.name;
                template.getElementById("description").innerText = area.description;

                latlng = area.location.split(",");
                center = new google.maps.LatLng(latlng[0], latlng[1]);
                createMap("cragid", center, crags, viewCrag, path, template);
                createList(crags, "cragid", viewCrag, "list", path, template);
                loadTemplateView(template);
                cragbook.trail.addCrumb(area.name, () => (viewArea(area.areaid)));
                createBreadcrumb();
            });
        })
    })
}

export { viewAreas, viewArea };