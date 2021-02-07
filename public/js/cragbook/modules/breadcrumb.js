import { createElement } from './helpers.js';

// controls the breadcrumb trail for site navigation
class Breadcrumbs {
    constructor() {
        this.crumbs = [];
        this.depth = 3;
    }

    addCrumb(name, destination) {
        if (this.crumbs.length > 0) {
            // don't add the same item twice
            for (let i in this.crumbs) {
                if (name == this.crumbs[i].name) {
                    this.crumbs.splice(i, 1);
                }
            }
            this.crumbs.push({ name: name, destination: destination });
        }
        else this.crumbs.push({ name: name, destination: destination });
        
        // remove oldest index at maximum depth
        if (this.crumbs.length > this.depth) this.crumbs.splice(0, 1);
    }

    getCrumbs() {
        return this.crumbs;
    }
}

// renders the breadcrumb trail in the view
function createBreadcrumb() {
    let i, a;
    let crumbs = cragbook.trail.getCrumbs();
    let breadcrumb = document.getElementById("breadcrumb");
    let div = createElement("div", [["class", "p-1"], ["style", "font-size: 0.75em"]]);

    for (i in crumbs) {
        a = document.createElement("a");
        a.innerText = crumbs[i].name;

        if (i != crumbs.length - 1) {
            a.innerText += " / ";
        }

        a.addEventListener("click", crumbs[i].destination);
        div.appendChild(a);
    }

    breadcrumb.appendChild(div);
}

export { Breadcrumbs, createBreadcrumb };