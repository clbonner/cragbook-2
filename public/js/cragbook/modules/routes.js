import { addClick, createElement } from './helpers.js';

// defines route list functionality for crag pages
class RouteList {

    constructor(jsonData) {
        this.all = jsonData;
        this.view = this.all.slice();
        this.discipline = 'all';
    }

    sortByName() {
        this.view.sort(function (a, b) {
            var x = a.name.toLowerCase();
            var y = b.name.toLowerCase();
            if (x < y) return -1;
            if (x > y) return 1;
            return 0;
        });

        return this.view;
    }

    sortByGrade() {
        let tradRoutes, sportRoutes, boulderProblems, i;

        switch (this.discipline) {
            case "all":
                tradRoutes = this.getTradRoutes();
                sportRoutes = this.getSportRoutes();
                boulderProblems = this.getBoulderProblems();

                tradRoutes = this.sortTradRoutes(tradRoutes);
                sportRoutes = this.sortSportRoutes(sportRoutes);
                boulderProblems = this.sortBoulderProblems(boulderProblems);

                this.view = [];

                for (i in tradRoutes) {
                    this.view.push(tradRoutes[i]);
                }

                for (i in boulderProblems) {
                    this.view.push(boulderProblems[i]);
                }
                
                for (i in sportRoutes) {
                    this.view.push(sportRoutes[i]);
                }

                this.discipline = "all";
                return this.view;

            case "trad":
                tradRoutes = this.sortTradRoutes(this.view);
                return this.view = tradRoutes;

            case "sport":
                sportRoutes = this.sortSportRoutes(this.view);
                return this.view = sportRoutes;

            case "boulder":
                boulderProblems = this.sortBoulderProblems(this.view);
                return this.view = boulderProblems;
        }
    }

    sortByStars() {
        this.view.sort(function (a, b) { return b.stars.length - a.stars.length });
        return this.view;
    }

    sortByLength() {
        this.view.sort(function (a, b) { return a.length - b.length });
        return this.view;
    }
                
    sortByFirstAscent() {
        this.view.sort(function (a, b) {
            var x = a.firstascent.toLowerCase();
            var y = b.firstascent.toLowerCase();
            if (x < y) return -1;
            if (x > y) return 1;
            return 0;
        });

        return this.view;
    }

    sortBySector() {
        if (this.view != 0)
            this.view.sort(function (a, b) { return a.orderid - b.orderid });
        else return 0;

        return this.view;
    }
                
    sortByCrag() {
        if (this.view != 0) {
            this.view.sort(function (a, b) {
                var x = a.cragName.toLowerCase();
                var y = b.cragName.toLowerCase();
                if (x < y) return -1;
                if (x > y) return 1;
                return 0;
            });
        }
        else return 0;

        return this.view;
    }

    // return all routes
    getAllRoutes() {
        var x;

        this.discipline = "all";

        if (this.all == 0)
            return this.view = 0;
        else
            return this.view = this.all.slice();
    }

    // extracts trad routes from array of all routes
    getTradRoutes() {
        var x;

        this.view = [];

        for (x in this.all) {
            if (this.all[x].discipline == 1)
                this.view.push(this.all[x]);
        }

        this.discipline = "trad";
        return this.view;
    }

    // extracts sport routes from a given arrary of routes
    getSportRoutes() {
        var x;

        this.view = [];

        for (x in this.all) {
            if (this.all[x].discipline == 2) {
                this.view.push(this.all[x]);
            }
        }

        this.discipline = "sport";
        return this.view;
    }

    // extracts boulder problems from a given arrary of routes
    getBoulderProblems() {
        var x;

        this.view = [];

        for (x in this.all) {
            if (this.all[x].discipline == 3) {
                this.view.push(this.all[x]);
            }
        }

        this.discipline = "boulder";
        return this.view;
    }

    sortTradRoutes(tradRoutes) {
        tradRoutes.sort(function (a, b) {
            var gradeA = a.grade.split(" ");
            var gradeB = b.grade.split(" ");

            if (gradeA[0] == gradeB[0]) {
                if (gradeA[1] < gradeB[1]) return -1;
                else if (gradeA[1] > gradeB[1]) return 1;
                else return 0;
            }
            else {
                a = britishGrade(a.grade);
                b = britishGrade(b.grade);

                if (a < b) return -1;
                else if (a > b) return 1;
                else return 0;
            }
        });

        return tradRoutes;
    }

    sortSportRoutes(sportRoutes) {
        sportRoutes.sort(function (a, b) {
            if (a.grade < b.grade) return -1;
            else if (a.grade > b.grade) return 1;
            else return 0;
        });

        return sportRoutes;
    }

    sortBoulderProblems(boulderProblems) {
        boulderProblems.sort(function (a, b) {
            if (a.grade == "VB") return -1;
            else if (b.grade == "VB") return 1;
            else if (a.grade < b.grade) return -1;
            else if (a.grade > b.grade) return 1;
            else return 0;
        });

        return boulderProblems;
    }

    gradeFilter(filter) {
        var x, routes = [];
        var pattern = new RegExp("^" + filter);

        switch (this.discipline) {
            case "trad":
                var tradRoutes = this.getTradRoutes();
                tradRoutes = this.sortTradRoutes(tradRoutes);

                this.view = tradRoutes;
                break;

            case "sport":
                var sportRoutes = this.getSportRoutes();
                sportRoutes = this.sortSportRoutes(sportRoutes);

                this.view = sportRoutes;
                break;

            case "boulder":
                var boulderProblems = this.getBoulderProblems();
                boulderProblems = this.sortBoulderProblems(boulderProblems);

                this.view = boulderProblems;
                break;
        }

        for (x in this.view) {
            if (pattern.test(this.view[x].grade)) {
                routes.push(this.view[x]);
            }
        }

        return this.view = routes;
    }

    // returns the percentage of grade ranges 0-3, 4-6, 7-9
    getGradeSummary() {
        let summary = {};
        let trad = this.getTradRoutes();
        let boulder = this.getBoulderProblems();
        let sport = this.getSportRoutes();
        
        summary = {
            Trad : this.getGradePercentages(trad, true),
            Boulder : this.getGradePercentages(boulder),
            Sport : this.getGradePercentages(sport),
        }

        return summary;
    }    
    
    // return percentage for each grade boundary
    getGradePercentages(routes, trad = false) {
        let routeCount = [];
        let green = new RegExp("[0-4]");
        let amber = new RegExp("[5-6]");
        let red = new RegExp("[7]");
        let black = new RegExp("8|9|10|11|12");
        let i, grade;

        // create a copy so we don't modify the original array
        routes = JSON.parse(JSON.stringify(routes));
        
        if (trad) {
            for (i in routes) {
                grade = routes[i].grade.split(" ");
                routes[i].grade = String(Math.floor(britishGrade(grade[0])));
            }
        }

        // initalise array elements
        for (i = 0; i < 4; i++) routeCount[i] = 0;

        if (routes.length == 0) {
            return 0;
        }
        else {
            for (i in routes) {
                if (green.test(routes[i].grade)) routeCount[0]++;
                else if (amber.test(routes[i].grade)) routeCount[1]++;
                else if (red.test(routes[i].grade)) routeCount[2]++;
                else if (black.test(routes[i].grade)) routeCount[3]++;
            }
            
            for (i in routeCount) {
                routeCount[i] = Math.ceil( (routeCount[i] / routes.length) * 100);
            }
    
            return routeCount;
        }
    }
}

// helper function for sorting british grades
function britishGrade(grade) {
    if (/^E$/.test(grade)) grade = 0;
    else if (/^M/.test(grade)) grade = 1;
    else if (/^D/.test(grade)) grade = 1.5;
    else if (/^HD/.test(grade)) grade = 2;
    else if (/^VD/.test(grade)) grade = 2.5;
    else if (/^HVD/.test(grade)) grade = 3;
    else if (/^MS/.test(grade)) grade = 3.5;
    else if (/^S/.test(grade)) grade = 4;
    else if (/^HS/.test(grade)) grade = 5;
    else if (/^MVS/.test(grade)) grade = 5.1;
    else if (/^VS/.test(grade)) grade = 5.2;
    else if (/^HVS/.test(grade)) grade = 6;
    else if (/^E1/.test(grade)) grade = 7;
    else if (/^E2/.test(grade)) grade = 7.1;
    else if (/^E3/.test(grade)) grade = 7.2;
    else if (/^E4/.test(grade)) grade = 7.3;
    else if (/^E5/.test(grade)) grade = 7.4;
    else if (/^E6/.test(grade)) grade = 8;
    else if (/^E7/.test(grade)) grade = 8.1;
    else if (/^E8/.test(grade)) grade = 8.2;
    else if (/^E9/.test(grade)) grade = 8.3;
    else if (/^E10/.test(grade)) grade = 8.4;
    else if (/^E11/.test(grade)) grade = 8.5;
    else if (/^MXS/.test(grade)) grade = 8.6;
    else if (/^XS/.test(grade)) grade = 8.7;
    else if (/^HXS/.test(grade)) grade = 8.8;
    return grade;
}

// takes an array of route objects as routes
function createRouteTable(list, template = document) {
    let rows, routeData, description, fragment, i;
    let routeTemplate = template.getElementById("routerows");
    
    // remove existing table rows, skipping the headers
    if (template == document) {
        rows = template.getElementById("routes").querySelectorAll("tr");
        for (i = 1; i < rows.length; i++) rows[i].remove();
    }

    // add table rows
    for (i in list) {
        fragment = routeTemplate.content.cloneNode(true);
        rows = fragment.querySelectorAll("tr");
        routeData = rows[0];
        description = rows[1];

        routeData = createRouteDataRow(routeData, list[i]);
        description = createDescriptionRow(description, list[i].description);

        template.getElementById("routes").appendChild(fragment);
    }
}

function createRouteDataRow(row, route) {
    let i, td;
    let theads = ["grade", "length", "sector"];
    
    row.addEventListener("click", function() { showOrHideDescription( this ) });

    // +/- icon column
    td = createElement("td");
    td.innerHTML = "&#8862";
    row.appendChild(td);

    // route name column
    td = createElement("td");
    td.innerText = `${route.name} `;
    for (i = 1; i <= route.stars; i++) td.innerHTML += "&#9733";
    row.appendChild(td);

    // other columns in theads
    for (i in theads) {
        td = createElement("td");
        td.innerText = route[theads[i]];
        row.appendChild(td);
    }

    return row;
}

function createDescriptionRow(row, description) {
    let td = createElement("td", [["colspan", "5"], ["class", "p-3"]]);
    td.innerText = description;
    row.addEventListener("click", function() { showOrHideDescription( this ) });
    row.appendChild(td);

    return row;
}


// add event listeners for the route filter
function setRouteFilter(routes, template) {
    addClick("all", () => (createRouteTable(routes.getAllRoutes())), template);
    addClick("trad", () => (createRouteTable(routes.getTradRoutes())), template);
    addClick("boulder", () => (createRouteTable(routes.getBoulderProblems())), template);
    addClick("sport", () => (createRouteTable(routes.getSportRoutes())), template);
}

// adds events listeners for the route table headers
function setRouteTableHeaderFilters(routes, template) {
    addClick("th_name", () => (createRouteTable(routes.sortByName())), template);
    addClick("th_grade", () => (createRouteTable(routes.sortByGrade())), template);
    addClick("th_length", () => (createRouteTable(routes.sortByLength())), template);
    addClick("th_sector", () => (createRouteTable(routes.sortBySector())), template);
}

// creates the grade summary graph
function createGradeSummary(routes, template) {
    let data = routes.getGradeSummary();
    let summary = template.getElementById("gradesummary");

    for (let i in data) {
        if (data[i] !== 0) {
            let item = summary.content.cloneNode(true);
            item.getElementById("summaryname").innerText = i;
            item.getElementById("green").setAttribute("style", `width: ${data[i][0]}%`);
            item.getElementById("amber").setAttribute("style", `width: ${data[i][1]}%`);
            item.getElementById("red").setAttribute("style", `width: ${data[i][2]}%`);
            item.getElementById("black").setAttribute("style", `width: ${data[i][3]}%`);
            template.getElementById("summary").append(item);
        }
    }
}

function showOrHideDescription(element) {
    // element clicked is route data row
    if (element.hasAttribute("role")) { //hide
        if (element.nextElementSibling.classList.contains("show")) {
            element.nextElementSibling.classList.remove("show");
            element.firstElementChild.innerHTML = "&#8862";
        }
        else { // show
            element.nextElementSibling.classList.add("show");
            element.firstElementChild.innerHTML = "&#8863";
        }
    } 

    // element clicked is description row
    else { // hide
        element.classList.remove("show");
        element.previousElementSibling.firstElementChild.innerHTML = "&#8862"; 
    }
}

export { RouteList, createGradeSummary, createRouteTable, setRouteFilter, setRouteTableHeaderFilters }