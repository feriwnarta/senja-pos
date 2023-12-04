document.addEventListener('set-width-title', () => {

    $(() => {
        changeWidthTitle();
    });

});

Livewire.on('show-toast', ({type, title, message, onYes, onNo}) => {
    console.log(type);
    switch (type) {
        case 'success':
            Toast.showSuccess(document.body, title, message);
            break;
        case 'error':
            Toast.showError(document.body, title, message);
            break;
        case 'info':
            Toast.showInfo(document.body, title, message);
            break;
        case 'confirm':
            Popup.confirm(title, message, 'warning', 'Oke', 'Batal', onYes, onNo);
            break;
        default:
            console.error('Tipe toast tidak valid');
    }
});


/**
 * fungis ini digunakan untuk memberikan underline dititle navbar
 */
function changeWidthTitle() {
    let widthTitleNavbar = $(".navbar-title").width();
    let offset = $(".navbar-title").offset().left;

    $("#title-divider").css({
        position: "relative",
        left: offset - 270,
    });
    $("#title-divider").width(widthTitleNavbar);
}


