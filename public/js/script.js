$(window).on('load', function () {
    changeWidthTitle();
})

document.addEventListener('update-menu', function () {
    updateAccordionActive();
});

document.addEventListener('set-width-title', () => {
    changeWidthTitle();
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

/**
 * fungsi ini berguna untuk membuka accordion menu secara dinamis berdasarkan url
 */
function updateAccordionActive() {
    // Ambil URL saat ini
    var currentURL = window.location.pathname;


    // Dapatkan semua elemen 'a' dalam Accordion
    var accordionLinks = $('.accordion-item a');

    accordionLinks.each(function () {
        if ($(this).attr('href') === currentURL) {

            $('.accordion-item').find('.accordion-button')

            const accordionBtn = $(this).closest('.accordion-item').find('.accordion-button');
            const collapseBtn = $(this).closest('.accordion-collapse');
            const itemBtn = $(this).find('button');


            collapseBtn.addClass('show');
            itemBtn.addClass('inner-menu-active');
            accordionBtn.attr('aria-expanded', 'true');
            accordionBtn.removeClass('collapsed')

        }
    });
}
