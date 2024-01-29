var triggerButton = document.querySelector("#sidebar-pos-trigger");
var closeOrderButton = document.querySelector(".order-detail-close");
var content = document.querySelector(".content");
var menuContent = document.querySelector(".menu-content");
var sidebarDetail = document.querySelector(".sidebar-order-detail");

// var categoryMenu = document.querySelector(".pos-category-wrapper");
// var activeOrderTable = document.querySelector(".active-order-table");
// var isOpen = document.querySelector(".is-opened");

var currentRoute = window.location.pathname;
// document.addEventListener("livewire:navigated", () => {
triggerButton.addEventListener("click", () => {
    sidebarDetail.classList.add("is-opened");

    content.style.marginRight = "410px";
    content.style.transition = "all 0.35s linear";

    menuContent.style.gap = "7px";
});
closeOrderButton.addEventListener("click", () => {
    sidebarDetail.classList.remove("is-opened");

    content.style.marginRight = "0px";
    content.style.transition = "all 0.35s linear";

    menuContent.style.gap = "8px";
});
// });
