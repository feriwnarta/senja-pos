var triggerButton = document.querySelector("#sidebar-pos-trigger");
var closeOrderButton = document.querySelector(".order-detail-close");
var content = document.querySelector(".content");
var sidebarDetail = document.querySelector(".sidebar-order-detail");

var isOpen = document.querySelector(".is-opened");

// document.addEventListener("livewire:navigated", () => {
triggerButton.addEventListener("click", () => {
    sidebarDetail.classList.add("is-opened");

    content.style.marginRight = "430px";
    content.style.transition = "all 0.3s linear";
});
closeOrderButton.addEventListener("click", () => {
    sidebarDetail.classList.remove("is-opened");

    content.style.marginRight = "0px";
    content.style.transition = "all 0.3s linear";
});
// });
