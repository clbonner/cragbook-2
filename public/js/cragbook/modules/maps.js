const defaultCenter = { lat: 51.4490382, lng: -2.5943542 };

// create google map
function createMap(id, center, list, dest, path, template) {
    let contentString, location, latlng, marker, title;
    let infowindow = new google.maps.InfoWindow();
    let canvas = template.getElementById("map");

    let map = new google.maps.Map(canvas, {
        zoom: 9,
        center: center,
        scrollwheel: false,
        mapTypeId: google.maps.MapTypeId.TERRAIN
    });

    // add markers for items with locations
    for (let i in list) {
        if (list[i].location != "") {
            // get area location
            location = list[i].location.split(",");
            latlng = new google.maps.LatLng(location[0], location[1]);

            // set marker
            marker = new google.maps.Marker({
                position: latlng,
                map: map,
                title: list[i].name
            });

            // set marker info window content
            contentString = document.createElement("div");
            contentString.addEventListener("click", function () { 
                history.pushState({page: path, id: list[i][id]}, "", "/" + path + "/" + list[i][id]);
                dest(list[i][id]);
            });

            title = document.createElement("h5");
            title.appendChild(document.createTextNode(list[i].name));

            contentString.appendChild(title);
            contentString.appendChild(document.createTextNode(list[i].description));

            marker.info = contentString;

            // add event listener for pin click
            marker.addListener('click', function () {
                infowindow.setContent(this.info);
                infowindow.open(map, this);
            });
        }
    }
}

export { createMap, defaultCenter };