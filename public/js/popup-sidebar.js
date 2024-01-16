const saveButton = document.querySelector(".modal-save-button");
const closeOrderButton = document.querySelector(".order-detail-close")
const content = document.querySelector(".content")
const sidebarDetail = document.querySelector(".sidebar-order-detail");

const isOpen = document.querySelector(".is-opened");

saveButton.addEventListener("click", ()=> {
    sidebarDetail.classList.add("is-opened");

    content.style.marginRight = "423px";
    content.style.transition = "0.2s";
});

closeOrderButton.addEventListener("click", ()=> {
    sidebarDetail.classList.remove("is-opened");

    content.style.marginRight = "0px";
    content.style.transition = "0.2s";
});