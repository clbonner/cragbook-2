
// creates a template from the given html and returns it as a docuument fragment
function createTemplate(html) {
    let template = createElement("template");
    template.innerHTML = html;
    return template.content;
}

// loads the main view in to the DOM
function loadTemplateView(template) {
    let view = document.getElementById("view");
    view.innerHTML = "";
    view.appendChild(template);
}

// checks response status for fetch requests and returns text
function getResponseText(response) {
    if (response.status == 200) {
        return response.text();
    }
    else {
        console.log("Error status: " + response.status);
    }
}

// creates a DOM element with optional attributes in array [[attr, value], [attr, value]....]
function createElement(element, attributes=[]) {
    let attr, i;
    let el = document.createElement(element);

    for (i in attributes) {
        attr = document.createAttribute(attributes[i][0]);
        attr.value = attributes[i][1];
        el.setAttributeNode(attr);
    }

    return el;
}

// creates and sets an attribute on the specified parent element
function setAttribute(parent, attr, value) {
    let attribute = document.createAttribute(attr);
    attribute.value = value;
    parent.setAttributeNode(attribute);
}

// take element to create, any additional class data and an id for the element - Bootstrap 4.5
function createCollapsableElement(element, customClass, id) {
    let element_class, element_id;
    let collapsableElement = document.createElement(element);

    element_class = document.createAttribute("class");
    element_class.value = `collapse ${customClass}`;
    element_id = document.createAttribute("id");
    element_id.value = id;
    collapsableElement.setAttributeNode(element_class, element_id);

    return collapsableElement;
}

// takes element to create and targetid of the element to control - Bootstrap 4.5
function createCollapsableControlElement(element, targetid) {
    let role, data_toggle, data_target, aria_controls, aria_expanded;
    let collapsableControlElement = document.createElement(element);

    data_toggle = document.createAttribute("data-toggle");
    data_target = document.createAttribute("data-target");
    aria_controls = document.createAttribute("aria-controls");
    aria_expanded = document.createAttribute("aria-expanded");

    data_toggle.value = "collapse";
    data_target.value = `#${targetid}`;
    aria_controls.value = targetid;
    aria_expanded.value = "false";

    if (element !== "button") {
        role = document.createAttribute("role");
        role.value = "button";
        collapsableControlElement.setAttributeNode(role);
    }

    collapsableControlElement.setAttributeNode(data_toggle);
    collapsableControlElement.setAttributeNode(data_target);
    collapsableControlElement.setAttributeNode(aria_controls);
    collapsableControlElement.setAttributeNode(aria_expanded);

    return collapsableControlElement;
}

// adds a click event to an element in the document or optional document fragment
function addClick(id, destination, fragment = null) {
    if (fragment === null) {
        document.getElementById(id).addEventListener("click", destination);
    }
    else {
        fragment.getElementById(id).addEventListener("click", destination);
    }
}

export { createElement, createTemplate, loadTemplateView, setAttribute, createCollapsableControlElement, 
    createCollapsableElement, addClick, getResponseText };