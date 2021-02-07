import { createElement } from './helpers.js';


// takes a sorted list a divides it in to alphabetical pages
class ListPagination {
    constructor(list, VIEW_MAX) {
        this.VIEW_MAX = VIEW_MAX;
        this.list = [];
        this.map = [];
        this.page = 0;
        this.start = 1;
        this.end = 0 + this.VIEW_MAX;

        this.mapList(list);
        this.PAGE_TOTAL = this.list.length;
    }

    mapList(list) {
        let i, item;
        let currentIndex = -1;

        for (i in list) {
            item = list[i].name[0].toUpperCase();

            if (!this.isAlreadyMapped(item)) {
                this.map.push(item);
                currentIndex++;
                this.list.push([list[i]]);
            }
            else this.list[currentIndex].push(list[i]);
        }
    }

    isAlreadyMapped(item) {
        let i;

        for (i in this.map) {
            if (item === this.map[i]) return true;
        }

        return false;
    }

    getListMap() {
        return this.map;
    }

    getList() {
        return this.list;
    }

    getPage(index) {
        if (index >= 0 && index < this.list.length) {
            this.page = index;
            return this.list[index];
        }
        else return this.list[this.page];
    }

    getNextPage() {
        if (this.page < this.list.length - 1) {
            return this.list[++this.page];
        }
        else return this.list[this.page];
    }

    getPreviousPage() {
        if (this.page > 0) {
            return this.list[--this.page];
        }
        else return this.list[this.page];
    }
}

// creates a list of either crags or areas
function createList(list, id, destination, element, template = document) {
    let li, a, i;
    let fragment = document.createDocumentFragment();
    if (template === document) template.getElementById(element).innerHTML = "";

    for (i in list) {
        li = createElement("li", [["class", "list-group-item p-2"]]);
        a = createElement("a", [["id", `id${list[i][id]}`], ["href", "#"]]);
        a.innerText = list[i].name;

        li.appendChild(a);
        fragment.appendChild(li);
    }

    // add event listeners
    for (let i in list) {
        fragment.getElementById(`id${list[i][id]}`)
            .addEventListener("click", function () {
                destination(list[i][id]);
            });
    }

    template.getElementById(element).appendChild(fragment);
}

// creates the list pagination control
function createPaginationControl(pagination, id, destination, element, template) {
    let previousButton, item, nextButton, map;
    let fragment = document.createDocumentFragment();

    previousButton = createPaginationButton("Previous");
    previousButton.addEventListener("click", () => {
        setCurrentPaginationItems(pagination, "previous");
        setCurrentPaginationButtons(pagination);
    });
    fragment.appendChild(previousButton);

    for (let i in map = pagination.getListMap()) {
        item = createPaginationButton(map[i]);   
        item.addEventListener("click", () => {
            createList(pagination.getPage(i), id, destination, element);
            setActivePaginationButton(pagination);
        });
        fragment.appendChild(item);
    }

    nextButton = createPaginationButton("Next");
    nextButton.addEventListener("click", () => {
        setCurrentPaginationItems(pagination, "next");
        setCurrentPaginationButtons(pagination);
    });
    fragment.appendChild(nextButton);
    template.getElementById("pagination").appendChild(fragment);

    setCurrentPaginationButtons(pagination, template);
    setActivePaginationButton(pagination, template);
}

// sets the current active button on the paginator after a new page has been retrieved
function setActivePaginationButton(pagination, template = document) {
    let control, currentItem, allItems;

    control = template.getElementById("pagination");
    currentItem = control.querySelector("li.page-item.active");
    if (currentItem !== null) currentItem.setAttribute("class", "page-item");

    allItems = control.querySelectorAll("li.page-item");
    allItems[parseInt(pagination.page) + 1].setAttribute("class", "page-item active");
}

// helper to create a pagination button
function createPaginationButton(buttonText) {
    let item = createElement("li", [["class", "page-item"]]);
    let item_link = createElement("a", [["class", "page-link"], ["href", "#"]]);
    
    item_link.innerText = buttonText;

    item.appendChild(item_link);
    return item;
}

// sets which items in the pagination map are shown when next or previous buttons are clicked
function setCurrentPaginationItems(pagination, id) {
    if (id == "next") {
        pagination.start += pagination.VIEW_MAX;
        pagination.end = pagination.start + pagination.VIEW_MAX;

        if (pagination.end > pagination.PAGE_TOTAL) {
            pagination.end = pagination.PAGE_TOTAL + 1;
            pagination.start = pagination.end - pagination.VIEW_MAX;
        }
    }
    else if (id == "previous") {
        pagination.start -= pagination.VIEW_MAX;
        pagination.end -= pagination.VIEW_MAX;

        if (pagination.start < 1) {
            pagination.start = 1;
            pagination.end = pagination.start + pagination.VIEW_MAX;
        }
    }
}

// sets which buttons are shown in the pagination view
function setCurrentPaginationButtons(pagination, template = document) {
    let control, buttons;
    let start = pagination.start;
    let end = start + pagination.VIEW_MAX;

    control = template.getElementById("pagination");
    buttons = control.querySelectorAll(".page-item");

    for (let i = 1; i <= pagination.PAGE_TOTAL; i++) {
        if (i >= start && i < end) {
            buttons[i].removeAttribute("style");
        }
        else {
            buttons[i].setAttribute("style", "display: none");
        }
    }

    setPaginationNavigationDisabled(pagination, buttons);
}

// controls whether the previous or next buttons are disabled
function setPaginationNavigationDisabled(pagination, buttons) {
    if (pagination.VIEW_MAX >= pagination.PAGE_TOTAL) {
        buttons[0].setAttribute("class", "page-item disabled");
        buttons[pagination.PAGE_TOTAL + 1].setAttribute("class", "page-item disabled");
    }
    else if (pagination.start == 1) {
        buttons[0].setAttribute("class", "page-item disabled");
        buttons[pagination.PAGE_TOTAL + 1].setAttribute("class", "page-item");
    }
    else if (pagination.end > pagination.PAGE_TOTAL) {
        buttons[0].setAttribute("class", "page-item");
        buttons[pagination.PAGE_TOTAL + 1].setAttribute("class", "page-item disabled");
    }
    else {
        buttons[0].setAttribute("class", "page-item");
        buttons[pagination.PAGE_TOTAL + 1].setAttribute("class", "page-item");
    }
}

export { ListPagination, createList, createPaginationControl };