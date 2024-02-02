var sidebarTrigger = document.querySelector("#sidebar-trigger");
var sidebarClose = document.querySelector(".order-detail-close");
var content = document.querySelector(".content");
var menuContent = document.querySelector(".menu-content");
var sidebarDetail = document.querySelector(".sidebar-order-detail");

sidebarTrigger.addEventListener("click", () => {
    content.style.marginRight = "410px";
    content.style.transition = "all 0.35s linear";
    menuContent.style.gap = "7px";
});

sidebarClose.addEventListener("click", () => {
    sidebarDetail.classList.remove("isOpened");

    content.style.marginRight = "0px";
    content.style.transition = "all 0.35s linear";
    menuContent.style.gap = "8px";
});
