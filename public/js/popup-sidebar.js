saveButton = document.querySelector(".sidebar-trigger");
closeOrderButton = document.querySelector(".order-detail-close");
content = document.querySelector(".content");
sidebarDetail = document.querySelector(".sidebar-order-detail");

isOpen = document.querySelector(".is-opened");

posMenu = document.querySelector("#pos-menu").getAttribute("href");

// document.addEventListener("livewire:navigated", () => {
saveButton.addEventListener("click", () => {
    sidebarDetail.classList.add("is-opened");

    content.style.marginRight = "430px";
    content.style.transition = "0.2s";
});
closeOrderButton.addEventListener("click", () => {
    sidebarDetail.classList.remove("is-opened");

    content.style.marginRight = "0px";
    content.style.transition = "0.2s";
});
// });
