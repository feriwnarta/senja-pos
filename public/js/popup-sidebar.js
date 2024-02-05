var content = document.querySelector(".content");
var menuContent = document.querySelector(".menu-content");

var shiftMainContent = document.querySelector("#shift-maincontent");
var sidebarOrderClose = document.querySelector(".sidebar-order");

shiftMainContent.addEventListener("click", () => {
    content.style.marginRight = "410px";
    content.style.transition = "all 0.35s linear";
    menuContent.style.gap = "7px";
});

sidebarOrderClose.addEventListener("click", () => {
    sidebarOrderClose.classList.remove("show");

    content.style.marginRight = "0px";
    content.style.transition = "all 0.35s linear";
    menuContent.style.gap = "8px";
});
