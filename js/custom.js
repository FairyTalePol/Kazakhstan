(function ($) {
    "use strict";

    $(window).scroll(function () {
        var window_top = $(window).scrollTop() + 1;
        if (window_top > 50) {
            $('.main_menu').addClass('menu_fixed animated fadeInDown');
        } else {
            $('.main_menu').removeClass('menu_fixed animated fadeInDown');
        }
    });

    const participant = $('.company-slider');
    if (participant.length) {
        participant.owlCarousel({
            items: 5,
            loop: true,
            dots: true,
            autoplay: true,
            autoHeight: true,
            autoplayHoverPause: true,
            autoplayTimeout: 5000,
            nav: false,
            smartSpeed: 2000,
        });
    }

    const offset = $('.main_menu').height();

    $('.navbar li a').click(function (event) {
        event.preventDefault();
        $($(this).attr('href'))[0].scrollIntoView();
        scrollBy(0, -offset);
    });

}(jQuery));
