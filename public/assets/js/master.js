var url = window.location;
const allLinks = document.querySelectorAll('ul.menu a');
console.log(allLinks);
const currentLink = [...allLinks].filter(e => {
    return e.href == url;
});
if (currentLink.length > 0) { //this filter because some links are not from menu
    const parent1 = currentLink[0].parentElement;
    parent1.classList.add('active')
    //check parent
    const parent2 = parent1.parentElement;

    if(parent2.classList.contains('submenu') || parent2.classList.contains('sidebar-item')){
        parent2.classList.add('active')
    }

    const parent3 = parent2.parentElement;

    if(parent3.classList.contains('submenu') || parent3.classList.contains('sidebar-item')){
        parent3.classList.add('active')
    }
    
    //currentLink[0].closest(".nav-treeview").style.display = "block";
    //currentLink[0].closest(".has-treeview").classList.add("active");
}


// JSTable

// Move "per page dropdown" selector element out of label
// to make it work with bootstrap 5. Add bs5 classes.
function adaptPageDropdown() {
    const selector = dataTable.wrapper.querySelector(".dataTable-selector")
    selector.parentNode.parentNode.insertBefore(selector, selector.parentNode)
    selector.classList.add("form-select")
}

// Add bs5 classes to pagination elements
function adaptPagination() {
    const paginations = dataTable.wrapper.querySelectorAll(
        "ul.dataTable-pagination-list"
    )

    for (const pagination of paginations) {
        pagination.classList.add(...["pagination", "pagination-primary"])
    }

    const paginationLis = dataTable.wrapper.querySelectorAll(
        "ul.dataTable-pagination-list li"
    )

    for (const paginationLi of paginationLis) {
        paginationLi.classList.add("page-item")
    }

    const paginationLinks = dataTable.wrapper.querySelectorAll(
        "ul.dataTable-pagination-list li a"
    )

    for (const paginationLink of paginationLinks) {
        paginationLink.classList.add("page-link")
    }
}